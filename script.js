function displayOptions(id) {

    let textId = "editInput"+id;
    let submitId = "editSubmit"+id;
    let deleteId = "delete"+id;
    let optionsId = "optionsToggle"+id;
    let selectId = "editSelect"+id;
    let newId = "newInput"+id;
    let newSubmit = "newSubmit"+id;


    if (document.getElementById(textId).style.display == "none") {
        document.getElementById(textId).style.display = "inline";
        document.getElementById(submitId).style.display = "inline";
        document.getElementById(optionsId).style.display = "inline";
        document.getElementById(selectId).style.display = "inline";
        document.getElementById(deleteId).style.display = "inline";
        document.getElementById(newId).style.display = "inline";
        document.getElementById(newSubmit).style.display = "inline";

    } else {
        document.getElementById(textId).style.display = "none";
        document.getElementById(submitId).style.display = "none";
        document.getElementById(optionsId).style.display = "none";
        document.getElementById(selectId).style.display = "none";
        document.getElementById(deleteId).style.display = "none";
        document.getElementById(newId).style.display = "none";
        document.getElementById(newSubmit).style.display = "none";
    }
}