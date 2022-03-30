(function () {

    let carouselImg = document.querySelector('.carousel-image');
    let arrowLeft = document.querySelector('.arrow-left');
    let arrowRight = document.querySelector('.arrow-right');
    let currImgIndex = 0;
    let pathToPhoto = 'images/photo-of-works/';

    let xhr = new XMLHttpRequest();
    xhr.open('GET', "/get-photo-names");

    //Json запрос на получение названий всех фотографий из папки(photo-of-works)
    xhr.onreadystatechange = function () {
        if(xhr.status === 200 && xhr.readyState === 4){

            let photoNames = (JSON.parse(xhr.responseText)).photoNames;
            photoNames.splice(0,2);

            //Перемешка массива названий фотографий в случайном порядке
            photoNames.sort(() => Math.random() - 0.5);

            let numberOfPhotos = photoNames.length;

            carouselImg.setAttribute('src', pathToPhoto + photoNames[currImgIndex]);

            //Обработчик события на клик по стрелке влево
            arrowLeft.onclick = function () {
                if(currImgIndex > 0){
                    carouselImg.setAttribute('src', pathToPhoto + photoNames[--currImgIndex]);
                }
            };

            //Обработчик события на клик по стрелке вправо
            arrowRight.onclick = function () {
                if(currImgIndex < numberOfPhotos -1){
                    carouselImg.setAttribute('src', pathToPhoto + photoNames[++currImgIndex]);
                }
            };
        }
    };

    xhr.send();
})();