function updateGradient() {
    const hours = new Date().getHours();
    const percentOfDay = hours / 24;
    let colors, angle, colorStops;
  
    // Define colors, angle, and color stop positions for different times of the day
    if (hours < 6) { // Night to Early Morning
      colors = ['#020111', '#20202c', '#403B4A', '#4F4F47'];
      angle = '135deg';
      colorStops = ['0%', '30%', '60%', '100%']; // simulate early morning light diffusion
    } else if (hours < 12) { // Early Morning to Noon
      colors = ['#4F4F47', '#E7A966', '#FAD6A5', '#FFBB78'];
      angle = '180deg';
      colorStops = ['0%', '25%', '50%', '100%']; // brightening sky towards midday
    } else if (hours < 18) { // Noon to Late Afternoon
      colors = ['#FFBB78', '#F08080', '#ED6A5A', '#833AB4'];
      angle = '225deg';
      colorStops = ['0%', '20%', '40%', '100%']; // sun moving lower, colors become more pronounced
    } else { // Late Afternoon to Night
      colors = ['#833AB4', '#FD1D1D', '#020111', '#000'];
      angle = '270deg';
      colorStops = ['0%', '30%', '70%', '100%']; // fading light into deep night
    }
  
    // Create gradient string with dynamic angle and color stop positions
    const gradient = `linear-gradient(${angle}, ${colors.map((color, index) => `${color} ${colorStops[index]}`).join(', ')})`;
    document.body.style.backgroundImage = gradient;
  }
  
  // Update the gradient immediately and then every minute to smoothly transition
  updateGradient();
  setInterval(updateGradient, 1000);
  