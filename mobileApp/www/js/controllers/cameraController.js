app.controller('CameraCtrl', function($scope,$http, Camera,$cordovaBarcodeScanner,$ionicPopup, Confirm,$window){

    $scope.myImage='';
    $scope.myCroppedImage='';

    var handleFileSelect=function(evt) {
        console.log("IN FUNCTION");
        var file=evt.currentTarget.files[0];
        var reader = new FileReader();
        reader.onload = function (evt) {
            $scope.$apply(function($scope){
                $scope.myImage=evt.target.result;
                //console.log($scope.myImage);
            });
        };
        reader.readAsDataURL(file);
    };
    angular.element(document.querySelector('#fileInput')).on('change',handleFileSelect);

    $scope.sendCropedPhoto = function(croppedImage) {

        console.log(croppedImage);
        alert("Image Send");
        $http.defaults.transformResponse = []; //get angular to not convert your data into an object
        var img = new Image();
        img.src =croppedImage;
        var canvas = document.createElement('canvas'),
            ctx = canvas.getContext('2d');
        // set its dimension to target size
        canvas.width = $scope.myCroppedOriginalW;
        canvas.height = $scope.myCroppedOriginalH;
        // draw source image into the off-screen canvas:
        ctx.drawImage(img, 0, 0, $scope.myCroppedOriginalW, $scope.myCroppedOriginalH);
        // encode image to data-uri with base64 version of compressed image
        var newbase64 = canvas.toDataURL("image/jpeg",1.0);

        console.log(newbase64);


        var blob = $scope.dataURItoBlob(newbase64);
        var uploadUrl = "http://83.212.118.7/camelot/api/ocrimg/ocr";

        var fd = new FormData();

        fd.append('photo', blob);
        fd.append('userid', 'last_day');

        $http.post(uploadUrl, fd, {
            transformRequest: angular.identity,
            headers: {'Content-Type': undefined}
        }).success(function (data, status, headers, config) {
            console.log(JSON.parse(data));

            Confirm.setReceipt(JSON.parse(data));
            $window.location.href='#/app/confirm';

        }).error(function (data, status, headers, config) {
            //$scope.status = status + ' ' + headers;
            $window.location.href='#/app/confirm';
            alert(status);
            console.log(data);
            console.log(status);
        });


    };

    $scope.getPhoto = function() {

        Camera.getPicture({
            quality: 100,
            saveToPhotoAlbum: false
        }).then(function (imageURI) {
            $scope.myImage = imageURI;
        }, function (err) {
            console.err(err);
        });
    };

    $scope.rotate= function(base64Image){

        var canvas  = document.createElement("canvas");
        var img = new Image();
        console.log(base64Image);
        img.src =base64Image;

        console.log(img.width);
        console.log(img.height);

        canvas.width  =img.height;
        canvas.height = img.width;

        var context = canvas.getContext("2d");
        context.translate(canvas.width, canvas.height/canvas.width);
        context.rotate( Math.PI / 2);

        context.drawImage(img, 0, 0);
        console.log(canvas.toDataURL("image/jpeg"));
        $scope.myImage =  canvas.toDataURL("image/jpeg");


    };

    $scope.dataURItoBlob = function (dataURI) {
        // convert base64/URLEncoded data component to raw binary data held in a string
        var byteString;
        if (dataURI.split(',')[0].indexOf('base64') >= 0)
            byteString = atob(dataURI.split(',')[1]);
        else
            byteString = unescape(dataURI.split(',')[1]);

        // separate out the mime component
        var mimeString = dataURI.split(',')[0].split(':')[1].split(';')[0];

        // write the bytes of the string to a typed array
        var ia = new Uint8Array(byteString.length);
        for (var i = 0; i < byteString.length; i++) {
            ia[i] = byteString.charCodeAt(i);
        }

        return new Blob([ia], {type:mimeString});
    };


    $scope.scanBarcode = function() {
        $cordovaBarcodeScanner.scan().then(function(imageData) {
            alert(imageData.text);
            console.log("Barcode Format -> " + imageData.format);
            console.log("Cancelled -> " + imageData.cancelled);
        }, function(error) {
            console.log("An error happened -> " + error);
        });
    };


});