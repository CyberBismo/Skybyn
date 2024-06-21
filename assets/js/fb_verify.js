const express = require('express');
const fetch = require('node-fetch');
const bodyParser = require('body-parser');

const app = express();
app.use(bodyParser.json());

app.post('/auth/facebook/callback', async (req, res) => {
  const { id, accessToken } = req.body;
  const appToken = '434789206198164|3dbf41ef97db7401966a5768043e6fa'; // Replace with your app token
  
  try {
    const response = await fetch(`https://graph.facebook.com/debug_token?input_token=${accessToken}&access_token=${appToken}`);
    const json = await response.json();
    
    if (json.data.is_valid && json.data.user_id === id) {
      // Token is valid
      // Here you would typically create or update the user in your database
      res.json({ success: true, user: req.body });
    } else {
      // Invalid token
      res.status(401).json({ success: false, message: 'Invalid token' });
    }
  } catch (error) {
    res.status(500).json({ success: false, message: 'Server error', error });
  }
});

app.listen(3000, () => console.log('Server running on port 3000'));
