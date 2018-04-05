 <div class="twob">
 
    <ul>
        <li class="foo">
            Administration du système
        </li>
    </ul>

    Enregistrer un nouvel utilisateur
    <br/><br/>
        <form name="newUserForm" action="./php/createUser.php" method="post">
          <span style="left">
            <input class="big" type="text" name="username" value="username" onfocus="this.value=''">
            <input class="big" type="text" name="password" value="password" onfocus="this.value=''">
          </span>
          <span style="float:right">
            <input type="submit" value="Ok">
          </span>
        </form>

    <br/><br/>
    
    Supprimer un utilisateur
    <br/><br/>
        <form name="deleteUserForm" action="./php/deleteUser.php" method="post" onsubmit="return confirm('Are you sure you want to delete this user ?');">
          <span style="left">
            <select name="username">
              <?php
                require './php/getUsersList.php';
                    foreach ($users as $i){
                        if ($i != "root"){
                            echo "<option>" . $i . "</option>\n";
                        }
                    }
              ?>
              </select>
          </span>
          <span style="float:right">
            <input type="submit" value="Ok">
          </span>
         </form>
</div>