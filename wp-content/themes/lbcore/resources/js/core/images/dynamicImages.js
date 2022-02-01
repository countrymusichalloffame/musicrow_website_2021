import "picturefill";
import "lazysizes";
import dynamicResponsiveImages from "./motif.dynamic-responsive-images";

export default function () {
    initDynamicResponsiveImages();
    disableRightClick();
}

function initDynamicResponsiveImages(
    startingPoint = document,
    IMAGE_CLASS = "js-dynamic-image",
    multiple = 1
) {
    const dynamicImages = startingPoint.querySelectorAll(`.${IMAGE_CLASS}`);

    if (dynamicImages.length) {
        dynamicResponsiveImages(IMAGE_CLASS, multiple);
    }
}

function disableRightClick() {
    // diable right click on images only.
    document.addEventListener("contextmenu", function(e) {
        //console.log('event detail', e.target.localName);
        if (e.target.localName == 'img') {
            e.preventDefault();
        }
    });
}