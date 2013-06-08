Utils.namespace( "window.nox" );

$( function(){
    "use strict";
    $('#index #create #email').keyup(
        function(){
            if( $(this).val() ){
                $('#verify_email').fadeIn(300);
            }
            else if( $(this).val() == '' ){
                $('#verify_email').fadeOut(300);
            }
        }
    );

    $('#index #create #password').keyup(
        function(){
            if( $(this).val() ){
                $('#verify_password').fadeIn(300);
            }
            else if( $(this).val() == '' ){
                $('#verify_password').fadeOut(300);
            }
        }
    );

    $('#index #create').submit(
        function(e){
            if( ($('#email').val() != $('#vemail').val()) ){
                $('#email').addClass('error');
                $('#vemail').addClass('error');
                e.preventDefault();
            }
            else{
                //allow
            }

            if( $('#password').val() != $('#vpass').val() ){
                e.preventDefault();
            }
            else{
                //allow
            }

        }
    );
}
