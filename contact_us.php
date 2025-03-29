<section class="page-section">
    <div class="container">
        <h2 class="text-center">Напишете ни имейл</h2>       
        <div class="container">
            <form action="" id="contact">

                <label for="fname">Име и Фамилия *</label>
                <input type="text" id="name" name="name" placeholder="Въведете име...">

                <label for="lname">Имейл *</label>
                <input type="text" id="email" name="email" placeholder="Въведете имейл...">
                
                <label for="subject">Тема *</label>
                <input type="text" id="subject" name="subject" placeholder="Озаглавете съобщението..."></input>

                <label for="subject">Съобщение *</label>
                <textarea id="message" name="message" placeholder="Напишете ни нещо..." style="height:200px"></textarea>

                <input type="submit" value="Изпрати" id="btn-send">

            </form>
    </div>
</section>
<style>
input[type=text], select, textarea {
  width: 100%; 
  padding: 12px; 
  border: 1px solid #ccc; 
  border-radius: 4px; 
  margin-top: 6px; 
  margin-bottom: 16px; 
  resize: vertical;
}
input[type=submit] {
  background-color: #04AA6D;
  color: white;
  padding: 12px 20px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}
</style>
<script>
   $('#contact').submit(function(e){
        e.preventDefault();
        start_load();

        var name = $('#name').val();
        var email = $('#email').val();
        var subject = $('#subject').val();
        var message = $('#message').val();           

        if(name != "" && email != "" && subject != "" && message != ""){
            if(IsEmail(email)){
                $.ajax({                    
                    url:'admin/ajax.php?action=send_feedback',
                    data: new FormData($(this)[0]),
                    cache: false,
                    contentType: false,
                    processData: false,
                    method: 'POST',
                    type: 'POST',
                    success: function(result){                                                                                                                    
                        if(result==200){
                            alert_toast("Съобщението е успешно изпратено",'success');
                            setTimeout(function(){
                                location.reload();
                            },250);
                        }
                    }
                });
            } else {
                $('#contact').prepend('<div class="alert alert-danger">Имейлът е невалиден</div>');	
                end_load();
            }
        } else{
            $('#contact').prepend('<div class="alert alert-danger">Попълни задължителните полета</div>');	
            end_load();
        }                    
    });
    
    function IsEmail(email) {
        var filter = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        if(!filter.test(email)) {
           return false;
        }else{
           return true;
        }
    }
</script>