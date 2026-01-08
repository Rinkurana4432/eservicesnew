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

function startPreloader(){
            var sw=$(document).width();
            var sh=$(document).height();
            $("#modalwindow").css("display","block")
            $("#modalwindow").css("width",sw+"px");
            $("#modalwindow").css("height",sh+"px");
            $("#modalwindow").removeClass("modalhide").addClass("modalshow");
        }

        function stopPreloader(){
            $("#modalwindow").css("display","none")
            $("#modalwindow").removeClass("modalshow").addClass("modalhide");
        }


 $(document).ready(function () {
    console.log('Document ready');
    var eighteenYearsAgo = new Date();
    eighteenYearsAgo.setFullYear(eighteenYearsAgo.getFullYear() - 18);
    $('#date_of_birth').datepicker({
        format: 'dd-M-yyyy',
        endDate: eighteenYearsAgo,
        autoclose: true
    });
});
