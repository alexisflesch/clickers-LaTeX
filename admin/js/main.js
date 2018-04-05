var select, makeListClickable;


function select(){
    //highlights the current poll
    console.log("highlighting current poll");
    jQuery.ajax({
        type: "POST",
        url: 'php/getPollNumber.php',
        dataType: 'json',
        data: {},

        success: function (obj, textstatus) {
                  if( !('error' in obj) ){
                      console.log("hop");
                      //unselect previously selected poll
                      var prec = document.getElementsByClassName("selected");
                      console.log(prec);
                      if (prec.length>0){
                        prec[0].className = "rep";
                      }
                      //highlights new poll
                      var num = obj.num;
                      console.log(num);
                      if (num>=0){
                        document.getElementById(num.toString()).className = "selected";
                      }
                  }
                  else {
                      console.log("nothing to update");
                  }
            }
        });
    };
    
    
    
    
    
function makeListClickable(){
    //Creates a function that is called when user clicks the list
    $("#myid li").click(function() {
        if (confirm("Choisir cette question et effacer les résultats précédents ?")) {
            jQuery.ajax({
            type: "POST",
            url: 'php/clickNewPoll.php',
            dataType: 'json',
            data: {question: this.id},
            success: function (obj, textstatus) {
                  if( !('error' in obj) ) {
                      console.log(obj.result);
                      console.log("Modifying poll...");
                      select();
                  }
                  else {
                      console.log(obj.error);
                  }
            }
        });
        } else {
            console.log("Aborting");
        }
    });
};


