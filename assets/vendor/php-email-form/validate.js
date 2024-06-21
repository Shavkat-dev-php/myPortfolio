(function () {
  "use strict";

  let forms = document.querySelectorAll('.php-email-form');

  forms.forEach(function (e) {
    e.addEventListener('submit', function (event) {
      event.preventDefault();

      let thisForm = this;

      let action = thisForm.getAttribute('action');
      let recaptcha = thisForm.getAttribute('data-recaptcha-site-key');

      if (!action) {
        displayError(thisForm, 'The form action property is not set!');
        return;
      }
      thisForm.querySelector('.loading').classList.add('d-block');
      thisForm.querySelector('.error-message').classList.remove('d-block');
      thisForm.querySelector('.sent-message').classList.remove('d-block');

      let formData = new FormData(thisForm);

      if (recaptcha) {
        if (typeof grecaptcha !== "undefined") {
          grecaptcha.ready(function () {
            try {
              grecaptcha.execute(recaptcha, { action: 'php_email_form_submit' })
                .then(token => {
                  formData.set('recaptcha-response', token);
                  php_email_form_submit(thisForm, action, formData);
                })
            } catch (error) {
              displayError(thisForm, error);
            }
          });
        } else {
          displayError(thisForm, 'The reCaptcha javascript API url is not loaded!')
        }
      } else {
        php_email_form_submit(thisForm, action, formData);
      }
    });
  });

  function php_email_form_submit(thisForm, action, formData) {
    fetch(action, {
      method: 'POST',
      body: formData,
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
      .then(response => {
        if (response.ok) {
          return response.json(); // Изменено на JSON
        } else {
          throw new Error(`${response.status} ${response.statusText} ${response.url}`);
        }
      })
      .then(data => {
        thisForm.querySelector('.loading').classList.remove('d-block');
        if (data.ok) {
          showSuccessMessage(thisForm);
        } else {
          throw new Error(data.error ? data.error : 'Form submission failed and no error message returned from: ' + action);
        }
      })
      .catch((error) => {
        displayError(thisForm, error);
      });
  }

  function showSuccessMessage(thisForm) {
    thisForm.querySelector('.sent-message').classList.add('d-block');
    thisForm.reset();
  }

  function displayError(thisForm, error) {
    thisForm.querySelector('.loading').classList.remove('d-block');
    thisForm.querySelector('.error-message').innerHTML = error;
    thisForm.querySelector('.error-message').classList.add('d-block');
  }

})();


function php_email_form_submit(thisForm, action, formData) {
  if (thisForm.querySelector('[name="g-recaptcha-response"]')) {
      let recaptchaValue = thisForm.querySelector('[name="g-recaptcha-response"]').value;
      action += '?recaptcha-response=' + recaptchaValue;
  }

  fetch(action, {
      method: 'POST',
      body: formData,
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
  })
      .then(response => {
          if (response.ok) {
              return response.json(); // Изменено на JSON
          } else {
              throw new Error(`${response.status} ${response.statusText} ${response.url}`);
          }
      })
      .then(data => {
          thisForm.querySelector('.loading').classList.remove('d-block');
          if (data.ok) {
              showSuccessMessage(thisForm);
          } else {
              throw new Error(data.error ? data.error : 'Form submission failed and no error message returned from: ' + action);
          }
      })
      .catch((error) => {
          displayError(thisForm, error);
      });
}
function showSuccessMessage(thisForm) {
  thisForm.querySelector('.sent-message').classList.add('d-block');
  thisForm.reset(); // Сброс формы
}