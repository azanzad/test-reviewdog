/**
 *  Form Wizard
 */

'use strict';

(function () {
  // flatpickrRange
  /*const flatpickrRange = document.querySelector('#dealDuration');
  if (flatpickrRange) {
    flatpickrRange.flatpickr({
      mode: 'range'
    });
  }*/

  // Init custom option check
  window.Helpers.initCustomOptionCheck();
  // Vertical Wizard
  // --------------------------------------------------------------------

  const wizardCreateDeal = document.querySelector('#wizard-create-deal');
  if (typeof wizardCreateDeal !== undefined && wizardCreateDeal !== null) {
    // Wizard form
    const wizardCreateDealForm = wizardCreateDeal.querySelector('#formStoreAdd');
    // Wizard steps
    const wizardCreateDealFormStep1 = wizardCreateDealForm.querySelector('#choose-store');
    const wizardCreateDealFormStep2 = wizardCreateDealForm.querySelector('#login-with-amazon');
    const wizardCreateDealFormStep3 = wizardCreateDealForm.querySelector('#enter-store-name');

    // Wizard next prev button
    const wizardCreateDealNext = [].slice.call(wizardCreateDealForm.querySelectorAll('.btn-next'));
    const wizardCreateDealPrev = [].slice.call(wizardCreateDealForm.querySelectorAll('.btn-prev'));

    let validationStepper = new Stepper(wizardCreateDeal, {
      linear: true
    });

    // Deal Type
    const FormValidation1 = FormValidation.formValidation(wizardCreateDealFormStep1, {
      fields: {
        store_type: {
          validators: {
            notEmpty: {
              message: 'Please select store type'
            },
          }
        }
      },

      plugins: {
        trigger: new FormValidation.plugins.Trigger(),
        bootstrap5: new FormValidation.plugins.Bootstrap5({
          // Use this for enabling/changing valid/invalid class
          // eleInvalidClass: '',
          eleValidClass: '',
          rowSelector: '.col-sm-6'
        }),
        autoFocus: new FormValidation.plugins.AutoFocus(),
        submitButton: new FormValidation.plugins.SubmitButton()
      }
    }).on('core.form.valid', function () {
      // Jump to the next step when all fields in the current step are valid
      validationStepper.next();
    });

    // select2 (Region)
    const dealRegion = $('#dealRegion');
    if (dealRegion.length) {
      dealRegion.wrap('<div class="position-relative"></div>');
      dealRegion
        .select2({
          placeholder: 'Select an region',
          dropdownParent: dealRegion.parent()
        })
        .on('change.select2', function () {
          // Revalidate the region field when an option is chosen
          FormValidation1.revalidateField('dealRegion');
        });
    }

    // Deal Details
    const FormValidation2 = FormValidation.formValidation(wizardCreateDealFormStep2, {
      /*fields: {
        // * Validate the fields here based on your requirements
        dealTitle: {
          validators: {
            notEmpty: {
              message: 'Please enter deal title'
            }
          }
        },
        dealCode: {
          validators: {
            notEmpty: {
              message: 'Please enter deal code'
            },
            stringLength: {
              min: 4,
              max: 10,
              message: 'The deal code must be more than 4 and less than 10 characters long'
            },
            regexp: {
              regexp: /^[A-Z0-9]+$/,
              message: 'The deal code can only consist of capital alphabetical and number'
            }
          }
        }
      },*/
      plugins: {
        trigger: new FormValidation.plugins.Trigger(),
        bootstrap5: new FormValidation.plugins.Bootstrap5({
          // Use this for enabling/changing valid/invalid class
          // eleInvalidClass: '',
          eleValidClass: '',
          rowSelector: '.col-sm-6'
        }),
        autoFocus: new FormValidation.plugins.AutoFocus(),
        submitButton: new FormValidation.plugins.SubmitButton()
      }
    }).on('core.form.valid', function () {
      // Jump to the next step when all fields in the current step are valid
      validationStepper.next();
    });

    // select2 (Offered Item)
    const dealOfferedItem = $('#dealOfferedItem');
    if (dealOfferedItem.length) {
      dealOfferedItem.wrap('<div class="position-relative"></div>');
      dealOfferedItem
        .select2({
          placeholder: 'Select an offered item',
          dropdownParent: dealOfferedItem.parent()
        })
        .on('change.select2', function () {
          // Revalidate the field if needed when an option is chosen
          // FormValidation2.revalidateField('dealOfferedItem');
        });
    }

    // Deal Usage
    const FormValidation3 = FormValidation.formValidation(wizardCreateDealFormStep3, {
      fields: {
        // * Validate the fields here based on your requirements
      },
      plugins: {
        trigger: new FormValidation.plugins.Trigger(),
        bootstrap5: new FormValidation.plugins.Bootstrap5({
          // Use this for enabling/changing valid/invalid class
          // eleInvalidClass: '',
          eleValidClass: '',
          rowSelector: '.col-sm-6'
        }),
        autoFocus: new FormValidation.plugins.AutoFocus(),
        submitButton: new FormValidation.plugins.SubmitButton()
      }
    }).on('core.form.valid', function () {
      validationStepper.next();
    });



    wizardCreateDealNext.forEach(item => {
      item.addEventListener('click', event => {
        // When click the Next button, we will validate the current step
        switch (validationStepper._currentIndex) {
          case 0:
            FormValidation1.validate();
            break;

          case 1:
            FormValidation2.validate();
            break;

          case 2:
            FormValidation3.validate();
            break;

          case 3:
            FormValidation4.validate();
            break;

          default:
            break;
        }
      });
    });

    wizardCreateDealPrev.forEach(item => {
      item.addEventListener('click', event => {
        switch (validationStepper._currentIndex) {
          case 3:
            validationStepper.previous();
            break;

          case 2:
            validationStepper.previous();
            break;

          case 1:
            validationStepper.previous();
            break;

          case 0:

          default:
            break;
        }
      });
    });
  }
})();
