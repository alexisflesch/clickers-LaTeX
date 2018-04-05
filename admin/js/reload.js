var firstUpdate, hideResults, showResults, writeQuestion, createStyle, createBar, createTitle

//Total number of votes (to know if we should update the results or not)
var lastNbVotes=0;
//Total number of possible responses to the vote
var totRep=0;

//Global variable to know if there is a vote in progress
var pollPending = 0;
//Global variable to know the current poll in order to change 
//the question dynamically
var currentPoll = -1;
//Global variable to know if the current poll should be updated
var updatePoll = false;
//Global variable 
var pollId;


function delayedMathJax(){
    //MathJax.Hub.Typeset is not a function
    //This is to be able to call it with setTimeout
    MathJax.Hub.Typeset();
}


function firstUpdateResults(){
    writeQuestion();
    setTimeout(showResults, 100);
};


function changePollId(){
    var newPollId = document.forms["idForm"]["id"].value;
    if (newPollId=="" || newPollId=="Please set an id"){
        alert("Pleaser enter a valid id");
        var textArea = document.forms["idForm"]["id"];
        if (pollId != "Please set an id"){
            textArea.value = pollId;
            return false;
        }
        else{
            textArea.className = "blink";
            textArea.setAttribute("onclick", "clearOnClick();");
            textArea.value = "Please set an id";
            return false;
        }
    }
    console.log(newPollId);
    console.log("Setting poll id");
    jQuery.ajax({
        type: "POST",
        url: 'php/setPollId.php',
        dataType: 'json',
        data: {id: newPollId},
        success: function (obj, textstatus) {
            console.log(obj);
                  if(!('error' in obj)) {
                      console.log(obj.result);
                      var textArea = document.forms["idForm"]["id"];
                      if (obj.result == "id already in use") {
                          alert("This id has already been taken by another user");
                          textArea.value = pollId;
                      }
                      else {
                          pollId = newPollId;
                      }
                      if (pollId == "Please set an id") {
                        textArea.className = "blink";
                        textArea.setAttribute("onclick", "clearOnClick();");
                      }
                      else {
                          console.log("plop");
                          pollId = newPollId;
                          disableClearOnClick();
                      }
                  }
                  else {
                      console.log("Error setting poll id");
                  }
            }
        });
};

function clearOnClick(){  
    var textArea = document.forms["idForm"]["id"];
    textArea.className = "noblink";
    textArea.value = "";
}

function disableClearOnClick(){
    var textArea = document.forms["idForm"]["id"];
    textArea.removeAttribute("onclick");
};


function getPollIdFromTimer(){
    console.log('Fetching poll id');
    jQuery.ajax({
        type: "POST",
        url: 'php/getPollId.php',
        dataType: 'json',
        data: {},
        success: function (obj, textstatus) {
            console.log(obj);
                  if(!('error' in obj)) {
                      console.log(obj.id);
                      pollId = obj.id;
                      var button = document.getElementById("voteid");
                      button.innerHTML = "Poll id : ";
                      button.innerHTML += pollId;
                  }
                  else {
                      console.log("Error getting poll id");
                  }
            }
        });
};

function getPollId(){
    console.log('Fetching poll id');
    jQuery.ajax({
        type: "POST",
        url: 'php/getPollId.php',
        dataType: 'json',
        data: {},

        success: function (obj, textstatus) {
            console.log(obj);
                  if(!('error' in obj)) {
                      console.log(obj.id);
                      pollId = obj.id;
                      var textArea = document.forms["idForm"]["id"];
                      textArea.value = pollId;
                      document.forms["idForm"]["id"].value = obj.id;
                      if (obj.id == "Please set an id") {
                        textArea.className = "blink";
                        textArea.setAttribute("onclick", "clearOnClick();");
                      }
                  }
                  else {
                      console.log("Error getting poll id");
                  }
            }
        });
};

function updateResults(){
    var button = document.getElementById('toggleButton');
    if (updatePoll){
        updatePoll = false;
        console.log("Updating question");
        writeQuestion();
        setTimeout(hideResults, 100);
        if (button.className == "toggled"){
            toggle();
        }
    }
    else{
        if (button.className == "toggled"){
            showResults();
        }
    }
};

function conditionalUpdate(){
    pollChanged();
    setTimeout(updateResults,100);
}

function updateFromResultsTimeShift(){
    if (updatePoll){
        updatePoll = false;
        console.log("Poll has changed, rewriting question...");
        writeQuestion();
        setTimeout(showResults(1),100);
    }
    else{
        showResults();
    }
}

function updateFromResults(){
    pollChanged();
    setTimeout(updateFromResultsTimeShift,100);
}

function hideResults(){
    console.log("hiding results");
    for (i = 0; i < totRep; i++){
        var pcel = document.getElementById('pc-'+i);
        pcel.style.visibility = "hidden";
        var bar = document.getElementById('li-'+i);
        bar.className = "normal";
        var q = document.getElementById("textTotVotes");
        q.style.visibility = "hidden";
    }
};


function showResults(forced=0){
    jQuery.ajax({
        type: "POST",
        url: 'php/getResults.php',
        dataType: 'json',
        data: {},
        success: function (obj, textstatus) {
                  if( !('error' in obj) && ((obj.nbVotes != lastNbVotes) || updatePoll || forced==1)) {
                      lastNbVotes = obj.nbVotes;
                      if (updatePoll){
                          console.log("Poll has changed, rewriting questions.");
                          updatePoll = false;
                          writeQuestion();
                      }
                      console.log("updating results");
                      console.log(obj.resultats);
                      for (i = 0; i < totRep; i++){
                          if (lastNbVotes == 0){
                              var pc = 0;
                          }
                          else{
                              var pc = Math.round(obj.resultats[i]/obj.nbVotes*10000);
                          }
                          real_pc = pc/100;
                          //On change les styles
                          var pcel = document.getElementById('pc-'+i);
                          pcel.style.visibility = "visible";
                          pcel.innerHTML = real_pc + '%';
                          createStyle(pc);
                          var bar = document.getElementById('li-'+i);
                          bar.className = "graph-" + pc;
                          //On affiche le nombre de réponses
                          var q = document.getElementById("textTotVotes");
                          q.style.visibility = "visible";
                          var q = document.getElementById("textTotVotes2");
                          q.innerHTML = obj.nbVotes;
                      }
                  }
                  else {
                      console.log("nothing to update");
                  }
            }
        });
    };


function writeQuestion(){
        jQuery.ajax({
        type: "POST",
        url: 'php/getCurrentQuestion.php',
        dataType: 'json',
        data: {},
        success: function (obj, textstatus) {
                  if( !('error' in obj) || forced==1) {
                      console.log("Writing question");
                      //On efface le contenu de la liste
                      liste = document.getElementById('plop');
                      liste.innerHTML = "";
                      liste.innerHTML += createTitle(obj.question);
                      totRep = obj.reponses.length;
                      for (i = 0; i < obj.reponses.length; i++){
                          liste.innerHTML += createBar(0, obj.reponses[i], i);
                      }
                      liste.innerHTML += createTotVotes();
                      setTimeout(delayedMathJax,100);
                  }
                  else {
                      console.log("nothing to update");
                  }
            }
        });
};

    
function createStyle(pc){
    //Create CSS style
    real_pc = pc/100;
    text = "li.graph-";
    text += pc;
    text += " {background: linear-gradient(to right, rgba(0,0,0,0) ";
    text += real_pc;
    text += "%, rgba(255,255,255,.2) 1%);";
    text += "opacity: 1";
    text += "}";
    var css = document.createElement("style");
    css.type = "text/css";
    css.innerHTML = text;
    document.body.appendChild(css);
};

function createBar(pc, rep, i){
    createStyle(pc);
    real_pc = pc/100;
    text = '<li id="li-' + i + '"' + ' class="graph-';
    text += pc;
    text += '">';
    text += '<span style="left">';
    text += rep;
    text += '</span><span id="pc-' + i + '" style="float:right;color:#1bbccb";>';
    text += real_pc;
    text += '%</span>';
    text += '</li>';
    return text;
};

function createTotVotes(){
    createStyle(10000);
    text = '<li id="textTotVotes" class="graph-10000">';
    text += '<span style="left">';
    text += "Nombre de votes :";
    text += '</span><span id="textTotVotes2" style="float:right;color:#1bbccb";>';
    text += "0";
    text += '</span>';
    text += '</li>';
    return text;
};

function createTitle(title){
    text = '<ul><li class="foo2">';
    text += '<span style="left">';
    text += title;
    text += '</span>';
    text += '<span id="questionTitle" style="float:right;color:#1bbccb";></span>';
    text += '</li></ul>\n';
    return text;
};


function updateFromIndex(){
    //Init stuff for index.php
    getPollStatus();
    setTimeout(updatePollStatusFromIndex,100);
};

function updatePollStatusFromIndex(){
    var tmp = document.getElementById("etatSondage");
    if (pollPending==1){
        console.log("Sondage ouvert");
        tmp.innerHTML = "Ouvert";
    }
    else{
        tmp.innerHTML = "Fermé";
        console.log("Sondage fermé");
    }    
};


function init(){
    console.log("initializing...");
    //On récupère la question
    writeQuestion();
    //Fermeture du vote en cours
    stopTimer();
    //Numéro du vote en cours
    initializePollNumber();
    //Mise en place du rafraîchissement automatique
    autoCaptions();
    //On cache les résultats
    setTimeout(hideResults,100);
    //Setting vote id
    getPollIdFromTimer();
};

function initializePollNumber(){
    //Find poll number (to be run at startup)
    console.log('Fetching poll number');
    jQuery.ajax({
        type: "POST",
        url: 'php/getPollNumber.php',
        dataType: 'json',
        data: {},
        success: function (obj, textstatus) {
                  if(!('error' in obj)) {
                      currentPoll = parseInt(obj.num);
                  }
                  else {
                      console.log("Error getting poll number");
                  }
            }
        });
};


function pollChanged(){
    //Find poll number
    console.log('Fetching poll number');
    jQuery.ajax({
        type: "POST",
        url: 'php/getPollNumber.php',
        dataType: 'json',
        data: {},
        success: function (obj, textstatus) {
                  if(!('error' in obj)) {
                      var newPoll = parseInt(obj.num);
                      if (newPoll == currentPoll || currentPoll==-1){
                          console.log("Poll hasn't changed");
                          currentPoll = newPoll;
                      }
                      else{
                          console.log("Poll has changed");
                          currentPoll = newPoll;
                          updatePoll = true;
                      }
                  }
                  else {
                      console.log("Error getting poll number");
                  }
            }
        });
};


function toggle(){
    //When "Show/Hide results" button is clicked
    console.log("Showing/hiding results");
    var button = document.getElementById('toggleButton');
    console.log(button.className);
    if (button.className == "untoggled"){
        button.className = "toggled";
        button.value = "Hide results";
        showResults(1);
    }
    else{
        button.className = "untoggled";
        button.value = "Show results";
        hideResults();
    }
};
    
function getPollStatus(){
    console.log('Fetching poll status');
    jQuery.ajax({
        type: "POST",
        url: 'php/getPollStatus.php',
        dataType: 'json',
        data: {},

        success: function (obj, textstatus) {
            console.log(obj);
                  if(!('error' in obj)) {
                      console.log(obj.status);
                      if (obj.status=="open"){
                        pollPending = 1;
                      }
                      else{
                        pollPending = 0;
                      }
                  }
                  else {
                      console.log("Error getting poll status");
                  }
            }
        });
};

function resetFromIndex(){
    jQuery.ajax({
        type: "POST",
        url: 'php/clearResults.php',
        });
    clearResults();
};


function resumeFromIndex(){
    resumePoll();
    var tmp = document.getElementById("etatSondage");
    tmp.innerHTML = "Ouvert";
    pollPending = 1;
};

function pauseFromIndex(){
    pausePoll();
    var tmp = document.getElementById("etatSondage");
    tmp.innerHTML = "Fermé";
    pollPending = 0;
};


function resetFromTimer(){
    clearResults();
    //Updating results
    var button = document.getElementById('toggleButton');
    if (button.className == "toggled"){
        showResults(1);
    }
    setTimeout(stopTimer, 100);    
}




function clearResults(){
    //Clear SQL
    jQuery.ajax({
        type: "POST",
        url: 'php/clearResults.php',
        });
};

function pausePoll(){
    //Remise à zéro de la BDD
    jQuery.ajax({
        type: "POST",
        url: 'php/pausePoll.php',
        });
};

function resumePoll(){
    //Remise à zéro de la BDD
    jQuery.ajax({
        type: "POST",
        url: 'php/resumePoll.php',
        });
};