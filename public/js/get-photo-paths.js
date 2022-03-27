(function () {

    let img = document.querySelector('.test');
    let str = document.querySelector('.str');
    let photos;
    let currentImgIndex = 0;

    let xhr = new XMLHttpRequest();
    xhr.open('GET', "/get-photo-paths");

    xhr.onreadystatechange = function () {
        if(xhr.status === 200 && xhr.readyState === 4){

            photos = JSON.parse(xhr.responseText);
            photos = photos.pathsToPhotos;
            photos.splice(0,2);

            let numberOfPhotos = photos.length;

            img.setAttribute('src', `images/photo-of-works/${photos[currentImgIndex]}`);
            str.onclick = function () {
                img.setAttribute('src', `images/photo-of-works/${photos[++currentImgIndex]}`);
            }
        }
    };

    xhr.send();
})();