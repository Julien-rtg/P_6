const addTagFormDeleteLink = (item, type) => {
    const removeFormButton = document.createElement('button');
    removeFormButton.classList.add('button');
    removeFormButton.classList.add('is-small');
    removeFormButton.classList.add('is-warning');
    removeFormButton.classList.add('mt-1');
    if (type == 'tagsPhoto'){
        removeFormButton.innerText = 'Supprimer cette photo';
    }else {
        removeFormButton.innerText = 'Supprimer cette vidéo';
    }

    item.append(removeFormButton);

    removeFormButton.addEventListener('click', (e) => {
        e.preventDefault();
        item.remove();
    });
};

const deletePhotoOrVideo = (id, type) =>{
    if(type == 'photo'){
        $('#form_update').append('<input type="hidden" name="photos_to_hide[]" value="' + id + '">');
        document.getElementById('photo_'+id).remove();
    }else if (type == 'video'){
        $('#form_update').append('<input type="hidden" name="videos_to_hide[]" value="' + id + '">');
        document.getElementById('video_'+id).remove();
    }
}

const addFormToCollection = (e) => {
    const classElement = e.currentTarget.dataset.collectionHolderClass;
    const collectionHolder = document.querySelector('.' + classElement);

    const item = document.createElement('div');
    const itemIndex = collectionHolder.dataset.index;
    
    item.innerHTML = collectionHolder.dataset.prototype.replace(/__name__/g, collectionHolder.dataset.index);
    
    if (classElement == 'tagsPhotoUpdate' || classElement == 'tagsVideoUpdate'){
        item.classList.add('column');
        item.classList.add('is-2');
    }
    
    collectionHolder.appendChild(item);

    if (classElement == 'tagsPhoto' || classElement == 'tagsPhotoUpdate') {
        const el = document.getElementById('trick_photoFigures_' + itemIndex + '_file');
        el.classList.add('input');
        el.classList.add('mt-4');
    } else {
        const el = document.getElementById('trick_videoFigures_' + itemIndex + '_path');
        el.classList.add('input');
        el.classList.add('mt-4');
        el.placeholder="Vidéo de la figure"
    }

    collectionHolder.dataset.index++;

    addTagFormDeleteLink(item, classElement);
};

const deleteFormInput = (e) => {
    e.preventDefault();
    const idLoop = e.target.id;

    item = document.getElementById('trick_videoFigures_'+idLoop+'_path');
    item2 = document.getElementById('error_'+idLoop);
    item3 = document.getElementById(idLoop);
    item.remove();
    item2.remove();
    item3.remove();
};

const deleteFormInputPhoto = (e) => {
    e.preventDefault();
    const idLoop = e.target.id;

    item = document.getElementById('trick_photoFigures_'+idLoop+'_file');
    item2 = document.getElementById('error_photo_'+idLoop);
    item3 = document.getElementById(idLoop);
    item.remove();
    item2.remove();
    item3.remove();
};

document.querySelectorAll('.add_item_link').forEach(btn => {
    btn.addEventListener("click", addFormToCollection)
});
document.querySelectorAll('.tricks_delete').forEach(btn => {
    btn.addEventListener("click", deleteFormInput)
});
document.querySelectorAll('.tricks_delete_photo').forEach(btn => {
    btn.addEventListener("click", deleteFormInputPhoto)
});

// Function to open the modal
const openModal = (item) => {
    // Add is-active class on the modal
    document.getElementById(item).classList.add("is-active");
}

// Function to close the modal
const closeModal = (item) => {
    document.getElementById(item).classList.remove("is-active");
}

// Add event listeners to close the modal
// whenever user click outside modal
document.querySelectorAll(".modal-background").forEach(($el) => {
    const $modal = $el.closest(".modal");
    $el.addEventListener("click", () => {
        // Remove the is-active class from the modal
        $modal.classList.remove("is-active");
    });
});

const changePreviewImage = (path, id, id_figure) => {
    let previewImage = document.getElementById('previewImage');
    let containerPreviewImage = document.getElementById('containerPreviewImage');

    const item = document.createElement('img');
    item.src = path;
    item.id = 'previewImage';
    item.classList.add('width100');
    previewImage.remove();

    containerPreviewImage.appendChild(item);

    closeModal('modalPreviewImage');

    $.ajax({
        type: "POST",
        url: '/tricks/editMainPicture/'+id,
        data: JSON.stringify(id_figure),
        contentType: 'application/json',
        success: function () {
            alert("Image à la une modifié avec succès");
        },
    });
}

const deletePreviewImage = (id_figure) => {

    let previewImage = document.getElementById('previewImage');
    let containerPreviewImage = document.getElementById('containerPreviewImage');

    const item = document.createElement('img');
    item.src = '/assets/img/base.jpg';
    item.id = 'previewImage';
    item.classList.add('width100');
    previewImage.remove();

    containerPreviewImage.appendChild(item);

    closeModal('modalDeleteImage');

    $.ajax({
        type: "POST",
        url: '/tricks/deleteMainPicture',
        data: JSON.stringify(id_figure),
        contentType: 'application/json',
        success: function () {
            alert("Image à la supprimé avec succès");
        },
    });
}

const deleteFigure = (id_figure) => {
    $.ajax({
        type: "POST",
        url: '/tricks/deleteFigure',
        data: JSON.stringify(id_figure),
        contentType: 'application/json',
        success: function () {
            window.location.href = '/?delete=true';
        },
    });
}

$(window).scroll(function () {
    if ($(window).scrollTop() + $(window).height() == $(document).height()) { // si on arrive a la fin de la page on load plus de figures
        let figures = document.querySelectorAll('.displayNoneFigure'); // on récupère les figures non affichées
        for (let i = 0; i < 10; i++){
            if(figures[i]){ // si la figure existe

                figures[i].classList.remove('displayNoneFigure'); // on enlève la classe qui supprime l'affichage
            }else {
                document.getElementById('hideWhenFullyFigure').classList.add('displayNone');
            }
        }
    }
});

const loadMoreComment = (url) => {
    $.ajax({
        url: url,
        type: 'POST',
        data: jQuery.param({ lastCom: document.getElementById("commentsChild").childElementCount, reset: false }),
        contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
        async: true,

        success: function (data) {
            console.log('ok');
            $('#commentsChild').remove();
            let div = document.createElement("div");
            div.setAttribute('id', 'commentsChild');
            $('#comments')[0].appendChild(div);
            $('#commentsChild').append(data.content);

        }
    });
}

const resetComment = (url) => {
    $.ajax({
        url: url,
        type: 'POST',
        data: jQuery.param({ reset: true }),
        contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
        async: true,

        success: function (data) {
            $('#commentsChild').remove();
            let div = document.createElement("div");
            div.setAttribute('id', 'commentsChild');
            $('#comments')[0].appendChild(div);
            $('#commentsChild').append(data.content);
        }
    });
}