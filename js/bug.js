const footer = document.querySelector(".bug_footer");
const bug_comment_new_form = document.querySelector(".bug_comment_new form");
const bug = document.querySelector(".bug");
const bug_comments_list = document.querySelector(".bug_comments_list");


const bugId = new URLSearchParams(window.location.search).get('bug_id');
sessionStorage.setItem("bug_id", bugId);



bug_comments_list.addEventListener("click",function(event) {
    if(event.target.tagName==="BUTTON"){
        if (event.target.name==="delete_comment"){
            const commentId = event.target.closest(".bug_comment").getAttribute("data-comment-id");
            console.log(commentId);
            deleteComment(commentId);
        }
        
    }
});


bug.addEventListener("click", function(ev){
    if(ev.target.classList.contains("bug_title")){
        const el = document.querySelector(".bug_title");
        if(el.getAttribute("contenteditable") === "true") return;
        const bugId = sessionStorage.getItem("bug_id");
        el.setAttribute("contenteditable", "true");
        el.focus();
        el.addEventListener("blur", function(){
            el.removeAttribute("contenteditable");
            changeBugTitle(bugId, el.textContent.trim());
        }, { once: true });
    }

    if(ev.target.classList.contains("bug_text")){
        const el = document.querySelector(".bug_text");
        if(el.getAttribute("contenteditable") === "true") return;
        const bugId = sessionStorage.getItem("bug_id");
        el.setAttribute("contenteditable", "true");
        el.focus();
        el.addEventListener("blur", function(){
            el.removeAttribute("contenteditable");
            changeBugDescription(bugId, el.textContent.trim());
        }, { once: true });
    }
});


/* footer.addEventListener("click",function(ev){
    const bugId =sessionStorage.getItem("bug_id");
    if(ev.target.tagName==="BUTTON"){
        buttonName=ev.target.name;
        if(buttonName==="reopen_bug"){
            BugReopened(bugId)   
        } else if (buttonName==="bug_set_fixed"){
            BugFixed(bugId);
        }
     }   
}) */

bug_comment_new_form.addEventListener("submit", function(event){
//form validation
    event.preventDefault(); // Prevent form submission
    const bug_comment_header = document.querySelector(".bug_comment_new form input[name='bug_comment_header']").value.trim();
    const textarea = document.querySelector(".bug_comment_new form textarea");
    if(textarea.value.trim() === ""){
        alert("Please enter a comment.");
        return;
    }

    createComment(bugId, bug_comment_header, textarea.value.trim());
});


 function BugFixed(bugId){
    const xhttp = new XMLHttpRequest();
          xhttp.onload = function() {
            alert("bug has been fixed;");
            footer.innerHTML="";
            footer.innerHTML="<button type='button' title='mark the bug as fixed' class='button small_button' name='reopen_bug'>Reopen</button><div class='span_modpack'>fixed</div>";
          }
          
        xhttp.open("POST", "bug_set_fixed.php",true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        var data = "bug_id="+encodeURIComponent(bugId);                
        xhttp.send(data);
}


function BugReopened(bugId){
      const xhttp = new XMLHttpRequest();
          xhttp.onload = function() {
            alert("bug has been reopened;")
            //footer remove all content
            footer.innerHTML="";
            //set new
            footer.innerHTML="<button type='button' title='mark the bug as fixed' name='bug_set_fixed' class='button'><i class='fa fa-check'></i></button>"
          }
          
        xhttp.open("POST", "bug_set_reopened.php",true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        var data = "bug_id="+encodeURIComponent(bugId);                
        xhttp.send(data);
}


function createComment(bugId, commentHeader, commentText){
    const xhttp = new XMLHttpRequest();
    xhttp.onload = function() {
    alert("comment added successfully;");
    //clear textarea and input
    document.querySelector(".bug_comment_new form textarea").value="";
    document.querySelector(".bug_comment_new form input[name='bug_comment_header']").value="";
    }
        
    xhttp.open("POST", "bug_comments_create.php",true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    var data = "bug_id="+encodeURIComponent(bugId)+"&comment_header="+encodeURIComponent(commentHeader)+"&comment_text="+encodeURIComponent(commentText);                
    xhttp.send(data);
}


function changeBugTitle(bugId, newTitle){
    const xhttp = new XMLHttpRequest();
    xhttp.onload = function() {
    alert("bug title changed successfully;");
    }
        
    xhttp.open("POST", "bug_title_change.php",true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    var data = "bug_id="+encodeURIComponent(bugId)+"&new_title="+encodeURIComponent(newTitle);                
    xhttp.send(data);
}


function changeBugDescription(bugId, newDescription){
    const xhttp = new XMLHttpRequest();
    xhttp.onload = function() {
    alert("bug description changed successfully;");
    }
        
    xhttp.open("POST", "bug_description_change.php",true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    var data = "bug_id="+encodeURIComponent(bugId)+"&new_description="+encodeURIComponent(newDescription);                
    xhttp.send(data);
}   

function deleteComment(commentId){
    const xhttp = new XMLHttpRequest();
    xhttp.onload = function() {
    document.querySelector(`.bug_comment[data-comment-id='${commentId}']`).remove();    
    alert("comment has been deleted;");
    }
        
    xhttp.open("POST", "bug_comments_remove.php",true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    var data = "comm_id="+encodeURIComponent(commentId);                
    xhttp.send(data);
}