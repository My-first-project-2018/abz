$(document).ready(() => {
    let
        body = $('body'),
        modal = $('.modal'),
        loginWindow = $('.login_window'),
        timer,
        draggable = false,
        dragItem = null,
        shiftY,
        shiftX,
        isOpened = false,
        leftPos,
        topPos,
        hierarchy;

//modal window

    $('.addUser').on('click', (e) => {
        e.preventDefault();
        modal.css({'display':'flex'});
    });


    $('.modal__close').on('click', () => {
        modal.css({'display':'none'});
    });


//dich
    

    body.click((e) => {
        //close login block
        if(!$(e.target).closest('.login').length && !$(e.target).closest('.login_window').length) loginWindow.slideUp();
    });
    

    $('.login').click(() => {
        loginWindow.slideToggle();
    });

    body.on('click', '.departments__item', openDepartment);

    body.on('click', '.close_department',  closeDepartmentItem);


    body.on('click','.subordinate__item', function () {
        //do not open subordinate when i drop item
        if(dragItem[0] === this && draggable) return;
        
        showSubordinate.call(this);
        
        if(!draggable) makeSubordinateActive.call(this);
    });

    function showSubordinate () {
        hierarchy = $(this).parent('.subordinate').attr('data-hierarchy');

        if(hierarchy == 5) return;

        let url = $(this).attr('data-url');

        $.ajax({
            url: url,
            type: 'GET',
            success: (html) => {
                if($(html).html() === 'No records') return false;
                $(this).closest('.departments__content').append(html);
                $('.departments__content').find('.subordinate:last-child').attr('data-hierarchy', +hierarchy + 1);

            }
        });
    }

    //drag'n'drop 

    body.on('mousedown', '.subordinate__item', function (e) {
        makeSubordinateDraggable.call(this, e);
    });



    body.on('mouseup', (e) => {
        dropDraggableItemAndFindDirector(e);
    });

    $(document).on('mousemove', (e) => {
       if(draggable)  moveItem(e);
    });

    //upload file

    $("input[type='file']").on('change', showUploadedImage);


    function moveItem (e) {
        dragItem.css({
            'position':'absolute',
            'left':`${e.clientX - shiftX}px`,
            'top':`${e.clientY - shiftY}px`,
            'z-index':'10'
        })
    }

    function openDepartment () {
        if(isOpened) {
            return;
        }
        let url = $(this).attr('data-url');

        $.ajax({
            url: url,
            type: 'GET',
            success: (html) => {
                $(this).append(html);
            }
        });
        //set absolute position to beauty animate
        leftPos = $(this).offset().left;
        topPos = $(this).offset().top;
        $(this).css({'left':`${leftPos - 20}px`});
        $(this).css({'top':`${topPos - 20}px`});
        //disable all other items
        setTimeout(() => {
            $(this).addClass('departments__item_active');
            $('.departments__item').each((i, item) => {
                if (item !== this) {
                    $(item).addClass('departments__item_disabled');
                }
            });
            isOpened = true;
        },1);
        //make visible department items
        setTimeout(() => {
            $(this).find('.close_department').addClass('close_department_active');
            $(this).find('.departments__content').addClass('visible');
        },400);
    }

    function closeDepartmentItem () {
        $(this).siblings('.departments__content').remove();
        let departmentItem = $(this).parent();
        departmentItem.find('.departments__content').removeClass('departments__content_active visible');
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
            $('.departments__item').each((i, item) => {
                if (item !== this) {
                    $(item).removeClass('departments__item_disabled');
                }
            });
            isOpened = false;
        },400);
    }

    function makeSubordinateActive () {
        $(this).parent().find('.subordinate__item').removeClass('subordinate__item_active');
        $(this).addClass('subordinate__item_active');

        $('.subordinate').each((i, item) => {
            if($(item).attr('data-hierarchy') > hierarchy + 2) {
                $(item).remove();
            }
        });
    }

    function makeSubordinateDraggable (e) {
        dragItem = $(this);
        if (dragItem.hasClass('subordinate__item_active')) return;
        shiftY = e.clientY - dragItem.offset().top;
        shiftX = e.clientX - dragItem.offset().left;
        timer = setTimeout(() => {
            draggable = true;
        },200);
    }

    function dropDraggableItemAndFindDirector (e) {
        clearTimeout(timer);
        if(draggable) {
            setTimeout(() => {
                draggable = false;
            },10);
            let item = $(e.target).closest('.subordinate__item');
            item.css({'display':'none'});
            let director = $(document.elementFromPoint(e.clientX, e.clientY)).closest('.subordinate__item');
            item.css({'display':'block'});
            console.log(director);
        }
        return false;
    }
    
    function showUploadedImage () {
        let file = this.files[0];

        if(file.type.match(/image/)) {
            $('.upload_image_container').html('');

            $('.upload_image').addClass('upload_image__active');

            let reader = new FileReader();

            reader.readAsDataURL(file);

            reader.onload = (function () {
                setTimeout(() => {
                    $('.upload_image').attr('src', reader.result);
                },100)

            })(file);

        } else {
            $('.upload_image').removeClass('upload_image__active').attr('src', '');
            $('.upload_image_container').html('THIS IS NOT IMAGE');
        }
    }

    

});


