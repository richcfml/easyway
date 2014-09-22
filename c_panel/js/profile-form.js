/*
 * Module for
 * Profile update/add form
 */
$(function(){

    /*
     * Form Progress Bar
     */
    var STYLE = {
        form_progress_wrapper : {
            display : 'none',
            width : '22%',
            height : '40px',
            marginTop:'-102px',
            marginLeft:'22px',
            position:'absolute'
        },
        form_progress : {
            width : '74%',
            height : '4px',
            'float' : 'left',
            margin : '18px 55px'
        },
        form_progress_bar : {
            width : '0%',
            height : '4px',
            backgroundColor : '#25AAE1'
        },
        form_progress_text : {
            position : 'relative',
            top : '-30px',
            right : '0px',
            width : '35px',
            fontStyle : 'italic',
            paddingTop : '10px',
            'float' : 'right',
            backgroundColor: '#25AAE1',
            textAlign: 'center',
            color:'white'
        }
    }
    var form;
    var submitButton;
    if(window.location.search.indexOf("addproduct") != -1){
         form = $('#add_item_form');
         submitButton = $('#userfile',form);
    }
    else{
        form = $('#update_item_form');
        submitButton = $('#userfile',form);
    }
    var progressWrapper = $('<div>').addClass('form-progress-wrapper').css(STYLE.form_progress_wrapper);
    var progress = $('<div>').addClass('form-progress').css(STYLE.form_progress);
    var progressBar = $('<div>').addClass('form-progress-bar').css(STYLE.form_progress_bar);
    var progressText = $('<span>').addClass('form-progress-text').css(STYLE.form_progress_text);

    progress.html( progressBar )
    progressBar.html( progressText )

    progressWrapper.html( progress );
    //progressWrapper.append( progressText );

    progressWrapper.insertAfter( submitButton );
    console.log(progressWrapper)


    function progressHandlingFunction(e){
        var perc = parseInt((e.loaded/e.total)*100);
        console.log('progress', {value:e.loaded,max:e.total});
        console.log('progress perd', perc);
        progressBar.css('width', perc+'%');
        progressText.html(perc+'%');
    }
    function beforeSendHandler(e){
        console.log('beforesend', e);

    }

    function completeHandler(e){
        console.log('complete', e);

    }

    function errorHandler(e){
        console.log('errorHandler', e);

    }
    
    submitButton.change(function(e){
       // e.preventDefault();
        var action = form[0].action;
        var formData = new FormData(form[0]);
        $.ajax({
            url: action,  //Server script to process data
            type: 'POST',
            xhr: function() {  // Custom XMLHttpRequest
                var myXhr = $.ajaxSettings.xhr();
                if(myXhr.upload){ // Check if upload property exists
                    myXhr.upload.addEventListener('progress',progressHandlingFunction, false); // For handling the progress of the upload
                }
                return myXhr;
            },
            //Ajax events
            beforeSend: beforeSendHandler,
            success: completeHandler,
            error: errorHandler,
            // Form data
            data: formData,
            //Options to tell jQuery not to process data or worry about content-type.
            cache: false,
            contentType: false,
            processData: false
        });

    });

    
})