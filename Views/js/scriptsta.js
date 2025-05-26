document.getElementById("archivo").addEventListener("change", function() {
    var fileName = this.files.length > 0 ? this.files[0].name : " ";
    document.getElementById("nombrea").textContent = fileName;
});