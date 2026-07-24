var categoryListContainer = document.getElementById('categories_list');
//console.log(categoryListContainer)
if (categoryListContainer) {
  // Attach a click event listener to the container
  categoryListContainer.addEventListener('click', function (event) {
    // Check if the clicked element has the tag name 'I'
    if (event.target.tagName === "I") {
      var dataId = event.target.closest('.category').getAttribute('data-id');
      //console.log(dataId);
      const container = document.querySelector(`.category[data-id="${dataId}"]`);
      //console.log(container);
      const catNameDiv = container.querySelector('.cat_name');
      const catNameText = catNameDiv.innerText; 
      const modNameSpan = document.querySelector(".mod_name");
      modNameSpan.innerText = catNameText;
      //console.log(catNameText);

      sessionStorage.setItem("mod_id", dataId);
      if (event.target.classList.contains('fa-plus-circle')) {
        //console.log("mod description");
        const modal = document.querySelector("#mod_description");
        if (modal) {
          //modal.style.display = "flex"; // Pridáme display: flex pred zobrazením
          modal.showModal(); // Zobrazí dialog ako modálne okno
        }
      } else if (event.target.classList.contains('fa-times-circle')) {
        //console.log("mod delete");
        remove_mod(dataId);
        //remove mod / div zo zoznamov modov
        //container.remove
      }
    }

    if(event.target.classList.contains("cat_name")){
        console.log(event.target.closest(".category").getAttribute("data-id"));
        const mod_id = event.target.closest(".category").getAttribute("data-id");
        window.location.href="mod.php?mod_id="+mod_id;
        
    }
  });
}


var action_container = document.querySelector("#mod_description .action");
action_container.addEventListener("click", (event)=> {
    if(event.target.tagName==="BUTTON"){

       mod_update_description();
    }
})


var letterList = document.getElementById("letter_list");
letterList.addEventListener("click", function(event){
    if(event.target.tagName==="BUTTON"){
        buttonName = event.target.name;
        if(buttonName==="all"){
            console.log("All mods...");
            SortMods("all");
        } else if (buttonName==="dupes"){
            console.log("Find all duplicates...");
            FindCategoriesDupes();
        } else {
            const char = event.target.innerText;
            SortMods(char)
        }
    }
})


function search_mods(mod) {
       var xhttp = new XMLHttpRequest();
       //var search_text=document.getElementById("search_string").value;
       xhttp.onreadystatechange = function() {
           if (this.readyState == 4 && this.status == 200) {
               document.getElementById("categories_list").innerHTML =
                   this.responseText;
           }
       };
       xhttp.open("GET", "categories_search.php?mod=" + encodeURIComponent(mod), true);
       xhttp.send();

   }


   function popup_search_mod(mod) {
       var xhttp = new XMLHttpRequest();
       //var search_text=document.getElementById("search_string").value;
       xhttp.onreadystatechange = function() {
           if (this.readyState == 4 && this.status == 200) {
               document.querySelector(".popup_mods_list main").innerHTML =
                   this.responseText;
           }
       };
       xhttp.open("GET", "modpack_mod_search.php?mod=" + encodeURIComponent(mod), true);
       xhttp.send();

   }


   function reload_modal_mods(){
      var input = document.querySelector(".popup_mods_list header input");
      var mod = input.value;

      const urlParams = new URLSearchParams(window.location.search);
      const modpack_id = urlParams.get('modpack_id');

      var xhttp = new XMLHttpRequest();
       //var search_text=document.getElementById("search_string").value;
       xhttp.onreadystatechange = function() {
           if (this.readyState == 4 && this.status == 200) {
               document.querySelector(".popup_mods_list main").innerHTML =
                   this.responseText;
           }
       };
       xhttp.open("GET", "modpack_modal_mod_reload.php?mod=" + encodeURIComponent(mod)+"&modpack_id="+modpack_id, true);
       xhttp.send();
   }

   function reload_mods(){
      var input = document.querySelector(".popup_mods_list header input");
      var mod = input.value;

      const urlParams = new URLSearchParams(window.location.search);
      const modpack_id = urlParams.get('modpack_id');

      var xhttp = new XMLHttpRequest();
       //var search_text=document.getElementById("search_string").value;
       xhttp.onreadystatechange = function() {
           if (this.readyState == 4 && this.status == 200) {
               document.querySelector(".modpack_mod_list").innerHTML =
                   this.responseText;
           }
       };
       xhttp.open("GET", "modpack_mods.php?mod=" + encodeURIComponent(mod)+"&modpack_id="+modpack_id, true);
       xhttp.send();
   }

      function remove_mod(id){
         console.log ("delete mod " + id);
          const xhttp = new XMLHttpRequest();
          xhttp.onload = function() {
            alert("Mod has been removed");
          }
          
        xhttp.open("POST", "category_delete.php",true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        var data = "cat_id="+encodeURIComponent(id);                
        xhttp.send(data);
       }
    
   function SortMods(char) {
       var xhttp = new XMLHttpRequest();
       //var search_text=document.getElementById("search_string").value;
       xhttp.onreadystatechange = function() {
           if (this.readyState == 4 && this.status == 200) {
               document.getElementById("categories_list").innerHTML =
                   this.responseText;
           }
       };
       xhttp.open("GET", "categories_sort.php?sort_by=" + encodeURIComponent(char), true);
       xhttp.send();

   }

   function FindCategoriesDupes() {
     var xhttp = new XMLHttpRequest();
       //var search_text=document.getElementById("search_string").value;
       xhttp.onreadystatechange = function() {
           if (this.readyState == 4 && this.status == 200) {
               document.getElementById("categories_list").innerHTML =
                   this.responseText;
           }
       };
       xhttp.open("GET", "categories_find_dupes.php", true);
       xhttp.send();
   }

   function mod_update_description() {
    const mod_id = sessionStorage.getItem("mod_id");
    const mod_description = document.querySelector('textarea[name="mod_description"]').value;
    const mod_url = document.querySelector('input[name="mod_url"]').value;
    const mod_image= document.querySelector('input[name="mod_image"]').value;

    if (mod_description === "") {
        alert("Description cannot be empty");
        return; // Zastaví ďalšie vykonávanie funkcie, ak je popis prázdny
    }
    if (mod_url === "") {
        alert("URL cannot be empty");
        return; // Zastaví ďalšie vykonávanie funkcie, ak je URL prázdne
    }

    var xhttp = new XMLHttpRequest();

    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            alert("Update successful");
            document.querySelector("#mod_description").close();            
            // Prípadne pridaj nejakú vizuálnu spätnú väzbu pre používateľa
        }
    };

    var data = "mod_description=" + encodeURIComponent(mod_description) + 
               "&mod_url=" + encodeURIComponent(mod_url) + 
               "&mod_id=" + encodeURIComponent(mod_id) + "&mod_image="+ encodeURIComponent(mod_image);
    
    console.log(data); // Na ladenie
    
    xhttp.open("POST", "categories_describe_mod.php", true);
    xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhttp.send(data);
}


function show_mod_information(mod_id){
     var xhttp = new XMLHttpRequest();
       xhttp.onreadystatechange = function() {
           if (this.readyState == 4 && this.status == 200) {
               
           }
       };
       xhttp.open("GET", "categories_show_info.php", true);
       xhttp.send();
}
