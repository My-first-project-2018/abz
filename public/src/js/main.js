$(document).ready(() => {

//modal window
    let verstka = '<p class="modal_text"> Verstka, eptet\'</p>';

    function openModal (content) {
        let modal = '<div class="modal"><div class="modal__close">X</div></div>';
        $('body').prepend(modal);
        $('.modal').append(content);

        $('.modal__close').on('click', () => {
            $('.modal').remove();
        });
    }

//dich

    let departments = $('.departments__item');
    let closeDepartment = $('.close_department');
    let loginWindow = $('.login_window');
    let isOpened = false;
    let leftPos, topPos;

    $('body').click((e) => {
        // if(!$(e.target).closest('.login').length) loginWindow.removeClass('login_window_active');
        if(!$(e.target).closest('.login').length) loginWindow.slideUp();

    });

    $('.login').click(() => {
        // loginWindow.toggleClass('login_window_active');
        loginWindow.slideToggle();
    });

    departments.on('click',function () {
        if(isOpened) {
            return;
        }
        leftPos = $(this).offset().left;
        topPos = $(this).offset().top;
        $(this).css({'left':`${leftPos - 20}px`});
        $(this).css({'top':`${topPos - 20}px`});
        setTimeout(() => {
            $(this).addClass('departments__item_active');
            departments.each((i, item) => {
                if (item !== this) {
                    $(item).addClass('departments__item_disabled');
                }
            });
            isOpened = true;
        },1);

        setTimeout(() => {
            $(this).find('.close_department').addClass('close_department_active');
        },300);

    });

    closeDepartment.on('click', function () {
        let departmentItem = $(this).parent();
        $(this).removeClass('close_department_active');
        departmentItem
            .removeClass('departments__item_active')
            .css({
                'position':'absolute',
                'left':`${leftPos}px !important`,
                'top':`${topPos}px !important`
            });
        setTimeout(() => {
            departmentItem.css({
                'position':'static',
                'left':'auto',
                'top':'auto'
            });
            departments.each((i, item) => {
                if (item !== this) {
                    $(item).removeClass('departments__item_disabled');
                }
            });
            isOpened = false;
        },400);
    });

});