(() => {
  'use strict';

  const $$ = (selector, scope = document) => [...scope.querySelectorAll(selector)];

  const setField = (field, message) => {
    field.classList.toggle('invalid', Boolean(message));
    const container = field.closest('.field') || field.parentElement;
    let error = container?.querySelector('.field-error');
    if (!error && container && message) {
      error = document.createElement('span');
      error.className = 'field-error';
      container.append(error);
    }
    if (error) error.textContent = message || '';
  };

  $$('[data-validate]').forEach((form) => {
    form.addEventListener('submit', async (e) => {
      e.preventDefault();
      const fields = $$('input, textarea, select', form).filter((f) => f.type !== 'hidden');
      let invalid = false;

      fields.forEach((field) => {
        let msg = '';
        const val = String(field.value || '');
        if (field.required && !val.trim()) {
          msg = 'This field is required.';
        } else if (field.type === 'email' && val && !field.validity.valid) {
          msg = 'Enter a valid email address.';
        } else if (field.type === 'password' && val && val.length < 8) {
          msg = 'Use at least 8 characters.';
        }
        if (field.name === 'password_confirmation' || field.name === 'confirm') {
          const pw = form.querySelector('[name="password"]');
          if (pw && val !== pw.value) {
            msg = 'Passwords do not match.';
          }
        }
        setField(field, msg);
        if (msg) invalid = true;
      });

      if (invalid) {
        window.showToast?.('Please check the highlighted fields.', 'error');
        fields.find((f) => f.classList.contains('invalid'))?.focus();
        return;
      }

      const submitBtn = form.querySelector('button[type="submit"]');
      const originalHtml = submitBtn ? submitBtn.innerHTML : '';
      if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="loader"></span><span>Processing…</span>';
      }

      const inlineStatus = form.querySelector('[data-form-status]');
      if (inlineStatus) inlineStatus.innerHTML = '';

      try {
        const data = new FormData(form);
        const response = await fetch(form.action || window.location.href, {
          method: 'POST',
          body: data,
          headers: { 'X-Requested-With': 'XMLHttpRequest' },
          credentials: 'same-origin',
        });

        const contentType = response.headers.get('content-type') || '';
        if (!contentType.includes('application/json')) {
          if (response.redirected) {
            window.location.href = response.url;
            return;
          }
          form.submit();
          return;
        }

        const result = await response.json();
        if (!response.ok) {
          throw new Error(result.message || 'Unable to process this request.');
        }

        window.showToast?.(result.message || 'Completed successfully.');
        if (result.redirect) {
          setTimeout(() => {
            window.location.href = result.redirect;
          }, 550);
        } else {
          form.reset();
          if (inlineStatus) {
            const box = document.createElement('div');
            box.className = 'alert success';
            box.style.marginTop = '16px';
            box.textContent = result.message || 'Your submission was received.';
            inlineStatus.append(box);
          }
          if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalHtml;
          }
        }
      } catch (err) {
        const msg = err?.message || 'Something went wrong. Please try again.';
        window.showToast?.(msg, 'error');
        if (inlineStatus) {
          const box = document.createElement('div');
          box.className = 'alert error';
          box.style.marginTop = '16px';
          box.textContent = msg;
          inlineStatus.append(box);
        }
        if (submitBtn) {
          submitBtn.disabled = false;
          submitBtn.innerHTML = originalHtml;
        }
      }
    });
  });

  $$('[data-password-toggle]').forEach((button) => {
    button.addEventListener('click', () => {
      const input = button.parentElement?.querySelector('input');
      if (!input) return;
      const visible = input.type === 'text';
      input.type = visible ? 'password' : 'text';
      button.textContent = visible ? 'Show' : 'Hide';
    });
  });
})();
