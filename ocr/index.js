// Import necessary libraries
const express = require('express');
const bodyParser = require('body-parser');
const axios = require('axios');  // For making requests to Statens Vegvesen API
const vision = require('@google-cloud/vision');  // Google Cloud Vision API

// Initialize Express app
const app = express();
app.use(bodyParser.json({ limit: '10mb' }));  // Increase body size limit to handle image data

// Google Cloud Vision Client
const client = new vision.ImageAnnotatorClient({
  keyFilename: 'google-cloud-credentials.json'  // Replace with path to your Google Cloud Vision credentials file
});

// Serve static files (like the index.html in /public)
app.use(express.static('public'));

// Route to detect plate from the image
app.post('/detect-plate', async (req, res) => {
  try {
    // Extract base64 image data from the request body
    const imageBase64 = req.body.image.split(',')[1];  // Remove data URL header

    // Call Google Cloud Vision API to detect text (plate number)
    const [result] = await client.textDetection({ image: { content: imageBase64 } });
    const detections = result.textAnnotations;

    if (detections.length > 0) {
      const plateNumber = detections[0].description.trim();

      // Validate plate with Statens Vegvesen API
      const apiResponse = await axios.get(`https://www.vegvesen.no/ws/no/vegvesen/kjoretoy/felles/datautlevering/enkeltoppslag/kjoretoydata?kjennemerke=${plateNumber}`, {
        headers: { 'SVV-Authorization': 'Apikey d2e8dde7-2f70-4622-af60-ac31d0da54a0' },  // Replace with your API key from Statens Vegvesen
      });

      const status = apiResponse.data ? 'Valid' : 'Invalid';

      // Return plate number and status to the front-end
      return res.json({ plate: plateNumber, status });
    } else {
      return res.json({ plate: 'No plate detected', status: 'Error' });
    }
  } catch (error) {
    console.error('Error detecting plate:', error);
    return res.status(500).json({ error: 'Failed to detect plate' });
  }
});

// Start the server on port 3000
app.listen(3000, () => {
  console.log('Server is running on http://localhost:3000');
});
