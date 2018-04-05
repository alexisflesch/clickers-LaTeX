$(window).load(function(){
    $("#myid li").click(function() {
        jQuery.ajax({
        type: "POST",
        url: 'php/clickAnswer.php',
        dataType: 'json',
        data: {answer: this.id, voteId: voteId},
        success: function (obj, textstatus) {
                  console.log(obj);
                  if( !('error' in obj) ) {
                      if ( obj.result=="ok"){
                          window.location.href = "out.php?res=ok";
                      }
                      else{
                        window.location.href = "out.php";
                      }
                  }
                  else {
                      console.log(obj.error);
                  }
            }
        });
    });
});