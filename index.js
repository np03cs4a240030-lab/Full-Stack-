document.addEventListener('DOMContentLoaded', () => {
  const video = document.getElementById('hero-video');
  const videoBtn = document.getElementById('toggle-video');

  function playPauseVideo(){
    if (video.paused) {
      video.play();
      videoBtn.textContent = 'Pause Video';
    } else {
      video.pause();
      videoBtn.textContent = 'Play Video';
    }
  };

  videoBtn.addEventListener('click',playPauseVideo);


  const audioButton = document.getElementById('toggle-audio');
  const audio = document.getElementById('sample-audio');
  audioButton.addEventListener('click',()=>{
    if(audio.paused) {
      audio.play();
      audioButton.textContent = 'Pause';
    }else{
      audio.pause();
      audioButton.textContent = 'Play';
    }   }
  );


  const canvas = document.getElementById('drawing-canvas');
  const ctx = canvas.getContext('2d');
  let isDrawing = false;
  let currentColor = '#000000';

  document.querySelectorAll('.color-picker button').forEach(btn => {
    btn.addEventListener('click', (e) => {
      currentColor = e.target.dataset.color;
      ctx.strokeStyle = currentColor;
    });
  });

  canvas.addEventListener('mousedown', (e) => {
    isDrawing = true;
    ctx.beginPath();
    ctx.moveTo(e.offsetX, e.offsetY);
  });

  canvas.addEventListener('mousemove', (e) => {
    if (isDrawing) {
      ctx.lineWidth = 6;
      ctx.lineCap = 'round';
      ctx.strokeStyle = currentColor;
      ctx.lineTo(e.offsetX, e.offsetY);
      ctx.stroke();
    }
  });

  canvas.addEventListener('mouseup', () => isDrawing = false);
  canvas.addEventListener('mouseout', () => isDrawing = false);


  document.getElementById('clear-canvas').addEventListener('click', () => {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
  });
  
  // Add Card when Button is Pressed
  const cardContainer = document.getElementById('card-container');
        const addCardBtn = document.getElementById('add-card-btn');
        let cardCount = 0;

        if (addCardBtn) {
             addCardBtn.addEventListener('click', () => {
                cardCount++;
                const newCard = document.createElement('div');
                newCard.className = 'p-4 bg-gray-50 border border-gray-200 rounded-lg shadow-sm';
                newCard.innerHTML = `<p class="font-medium text-sm">Card ${cardCount}</p><p class="text-xs">Added at ${new Date().toLocaleTimeString()}</p>`;
                cardContainer.prepend(newCard); // Add to the beginning
            });
        }
});

