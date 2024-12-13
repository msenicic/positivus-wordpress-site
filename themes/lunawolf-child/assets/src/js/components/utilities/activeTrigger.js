export default () => {
  let activeTriggers = document.querySelectorAll('.js-activeTrigger');

  activeTriggers.forEach(trigger => {
    trigger.addEventListener('click', e => {
      e.preventDefault();
      let dataBody    = trigger.dataset.body_class;
      let focusTarget = trigger.dataset.focus_target;

      trigger.classList.toggle('-active');

      if (dataBody) {
        document.body.classList.toggle(dataBody);
      }

      if (focusTarget) {
        setTimeout(() => {
          document.querySelector(focusTarget).focus();
        }, 300);
      }
    });
  });
}