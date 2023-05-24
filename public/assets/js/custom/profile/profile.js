// Update/reset user image of profile page
let userProfile = document.getElementById('userProfile');
let accountUserImage = document.getElementById('uploadedAvatar');
const fileInput = document.querySelector('.account-file-input'),
    resetFileInput = document.querySelector('.account-image-reset');

if (accountUserImage) {
    const resetImage = accountUserImage.src;
   
    fileInput.onchange = () => {
        if (fileInput.files[0]) {
            accountUserImage.src = window.URL.createObjectURL(fileInput.files[0]);
        }
    };
    resetFileInput.onclick = () => {
        var new_img = accountUserImage.src;    
        fileInput.value = '';
        userProfile.value='';
        accountUserImage.src = default_profile_image;
        // if(new_img != resetImage ){
        //     accountUserImage.src = resetImage;
        // }else{
        //     userProfile.value='';
        //      accountUserImage.src = default_profile_image;
        // }
       
    };
}

$("#contact_number").intlTelInput({
    initialCountry: "us",
});

$(document).ready(function () {
    contactDetailsLoad();
});
setTimeout(function(){
    $('.iti.iti--allow-dropdown').css('width','100%');
},100);
