document.addEventListener('DOMContentLoaded', function () {

    var inspectionBtn = document.getElementById('inspection_report');

    if (inspectionBtn) {
        inspectionBtn.addEventListener('click', function (e) {
            if (!validate_date()) {
                e.preventDefault();
            }
        });
    }

});

function validate_date()
{
    let from_date = document.getElementById('from_date').value;
    let to_date   = document.getElementById('to_date').value;
    let error     = '';

    if (!from_date) {
        error = 'Please select starting date first';
    } else {

        if (!to_date) {
            document.getElementById('to_date').value = from_date;
            to_date = from_date;
        }

        let from = new Date(from_date);
        let to   = new Date(to_date);
        let diffDays = (to - from) / (1000 * 60 * 60 * 24);

        if (from > to) {
            error = 'From-Date is greater than To-Date';
        } else if (diffDays > 31) {
            error = 'You can view records of maximum 31 days';
        }
    }

    let errBox = document.getElementById('progress_report');

    if (error) {
        errBox.innerHTML = error;
        errBox.style.display = 'block';
        return false;
    }

    errBox.style.display = 'none';
    return true;
}


document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.numbersOnly').forEach(function (input) {
        input.addEventListener('keydown', function (e) {
            const allowedKeys = [
                'Backspace', 'Tab', 'Delete',
                'ArrowLeft', 'ArrowRight',
                'Home', 'End'
            ];

            // Allow control keys
            if (allowedKeys.includes(e.key)) return;

            // Allow numbers only
            if (!/^[0-9]$/.test(e.key)) {
                e.preventDefault();
            }
        });

    });
});


document.addEventListener('DOMContentLoaded', function () {
 const BASE_URL = document.body.getAttribute("data-baseurl");


    document.addEventListener('click', function (e) {
        if (e.target && e.target.id === 'sendOtp') {

           var mobNo = document.getElementById("mobile_number").value;
           var csrf = document.getElementById("csrf_token").value;
          alert(csrf);
            if (mobNo.length < 1) {
                alert("Please enter mobile number");

            } else if (isNaN(mobNo)) {
                alert("Please enter valid mobile number");

            } else if (mobNo.length !== 10) {
                alert("Please enter valid mobile number");

            } else {
                $("#error").hide("slow");

                // Start loader
                startPreloader();

                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: BASE_URL + "/registration/sendotp",
                    data: {
                        mobile_number: mobNo,
                        csrf: csrf
                    },
                    success: function (data) {
                        stopPreloader();

                        if (data === 'MISSING_PARAMS') {
                            alert("Missing parameters");

                        } else if (data === 'CSRF_ERROR') {
                            alert("Fraudulent data :: Bad Request");

                        } else if (data.error === 'ALREADY_REGISTERED') {
                            alert("A user with provided no. already exists!");

                        } else if (data.STATUS_ID === "111" || data.STATUS_ID === "222") {
                            alert(data.RESPONSE);

                        } else if (data.STATUS_ID === "000") {
                            $("#hmac_token").val(data.hmac_token);

                            var encodedMob = $.base64.encode(mobNo);
                            $("#otp_mobile_number").val(encodedMob);

                            $("#mobile_number").val("");
                            $('#sendotp').hide();
                            $('#veriftotp').show();

                        } else if (data.STATUS_ID === "555") {
                            alert(data.RESPONSE || "Unable to send otp! Please try again later.");

                        } else {
                            alert("Unable to process request! Please try again");
                        }
                    }
                });
            }
        }
    });

});