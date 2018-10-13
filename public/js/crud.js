$(document).ready(() => {








    let department = $('#department');
    let page = 2;

    let newEmployees = null;

    addEmployeesScrollEvent($('.employees'));



    department.on('change', function (e) {
        // console.log(this.value)
        let url = this.value;
        console.log(url)
        $.ajax({
            url: url,
            method: "GET",
            success: (content) => {
                $('.employees').remove();
                $('.crud__content').append(content);
                setTimeout(() => {
                    newEmployees = $('.employees');
                    addEmployeesScrollEvent(newEmployees);
                },10);

            }
        })
    });



function addEmployeesScrollEvent (newEmployees) {
    newEmployees.on('scroll', function () {
        if ((this.scrollHeight - $(this).height()) === $(this).scrollTop()) {



            let departmentVal = department.val();
            let hash = getHashFromUrl(departmentVal);



            let attr = $(this).attr('current_page');
            let maxPage = $(this).attr('last_page');
            console.log(departmentVal);
            if(page > maxPage) return;
            let newAttr = attr + `/${hash}?page=${page}`;
            // console.log(newAttr);
            $.ajax({
                url: newAttr,
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                method: "GET",
                success: (result) => {
                    $(this).append(result);
                    // console.log(result)
                }
            });

            $(this).attr('current_page', newAttr);
            page++;
        }
    })
}




});