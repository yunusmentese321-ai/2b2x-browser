(() => {
  'use strict';

  const buttons = [...document.querySelectorAll('[data-download]')];

  const triggerDownload = async (button) => {
    if (button.disabled) return;
    button.disabled = true;
    const originalHtml = button.innerHTML;
    button.innerHTML = '<span class="loader"></span><span>Preparing installer…</span>';

    try {
      const url = button.dataset.download;
      if (!url) throw new Error('Download URL is unavailable.');
      const response = await fetch(url, { credentials: 'same-origin' });
      if (!response.ok) {
        throw new Error('The installer could not be retrieved right now.');
      }
      const blob = await response.blob();
      const objectUrl = URL.createObjectURL(blob);
      const anchor = document.createElement('a');
      anchor.href = objectUrl;
      anchor.download = button.dataset.filename || '2b2x-Browser-Setup.exe';
      document.body.append(anchor);
      anchor.click();
      anchor.remove();
      setTimeout(() => URL.revokeObjectURL(objectUrl), 1500);
      window.showToast?.('Your 2B2X Browser download has started.');
    } catch (error) {
      window.showToast?.(error?.message || 'Download failed.', 'error');
    } finally {
      button.disabled = false;
      button.innerHTML = originalHtml;
    }
  };

  buttons.forEach((button) => {
    button.addEventListener('click', (e) => {
      e.preventDefault();
      triggerDownload(button);
    });
  });
})();
