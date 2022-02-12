console.log(document.getElementById("queue-test-form"));

// document.getElementById("edit-captcha-button").addEventListener("click",(e) => {
//   e.preventDefault();
//   console.log(grecaptcha);
//
//   grecaptcha.execute('6LcLR3MeAAAAAIMThlgpmc_m5vVnmWWEQmyl_jbu', {action: 'submit'}).then(function(token) {
//     // Add your logic to submit to your backend server here.
//     console.log('debugging backend token!!! ', token);
//   });
// });

function onSubmit() {
  console.log('submit button triggered!!!');
  // document.getElementById("queue-test-form").submit();
}
