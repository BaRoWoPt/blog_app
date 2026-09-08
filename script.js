document.addEventListener('DOMContentLoaded', () => {
  const button = document.querySelector('#demo-btn');
  const message = document.querySelector('#message');

  if (button && message) {
    button.addEventListener('click', () => {
      const name = document.querySelector('#name')?.value || 'bạn';
      message.textContent = `Xin chào ${name}! Giao diện đã sẵn sàng để phát triển tiếp.`;
      message.style.color = '#22c55e';
    });
  }
});
