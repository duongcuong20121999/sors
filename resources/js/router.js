
const Size = Quill.import('attributors/style/size');
Size.whitelist = ['8px', '9px', '10px', '11px', '12px', '13px', '14px', '15px', '16px', '17px', '18px'];
Quill.register(Size, true);



function initializePageEvents() {
    const btnMain = document.querySelector(".button-text-main");
    const btnService = document.querySelector(".button-text-service");
    const mainScreen = document.querySelector(".main-screen");
    const serviceContainer = document.querySelector(".container-service-configuration");

    if (btnMain && btnService) {
        btnMain.addEventListener("click", function () {
            resetDropdown();
            const selectedImage = document.querySelector("#selected-image");
            if (selectedImage) {
                selectedImage.src = defaultImageSrc;
            }
            if (mainScreen) mainScreen.classList.toggle("show");
            if (serviceContainer) serviceContainer.classList.remove("show");
            btnMain.classList.toggle("active");
            btnService.classList.remove("active");
            document.querySelectorAll("input[type='checkbox']").forEach(checkbox => {
                checkbox.checked = false;
            });
        });

        btnService.addEventListener("click", function () {
            resetDropdown();
            const selectedImage = document.querySelector("#selected-image");
            if (selectedImage) {
                selectedImage.src = defaultImageSrc;
            }
            if (mainScreen) mainScreen.classList.remove("show");
            if (serviceContainer) serviceContainer.classList.toggle("show");
            btnMain.classList.remove("active");
            btnService.classList.toggle("active");

            if (serviceContainer && serviceContainer.classList.contains("show")) {
                const serviceBoxes = document.querySelectorAll(".box-service");
                const displayService = document.querySelector(".display-service");
                const serviceNameInput = document.querySelector(".service-name input");
                const serviceDescriptionTextarea = document.querySelector(".service-description textarea");
                const selectedImage = document.querySelector("#selected-image");

                if (serviceBoxes.length > 0 && displayService && serviceNameInput && serviceDescriptionTextarea && selectedImage) {
                    const firstServiceBox = serviceBoxes[0];
                    const serviceName = firstServiceBox.querySelector("p").innerText;
                    const serviceImage = firstServiceBox.querySelector("img").src;

                    displayService.style.display = "block";
                    serviceNameInput.value = serviceName;
                    serviceDescriptionTextarea.value = serviceName;

                    selectedImage.src = serviceImage;

                    firstServiceBox.classList.add("active");
                    document.querySelectorAll("input[type='checkbox']").forEach(checkbox => {
                        checkbox.checked = false;
                    });
                }
            }
        });
    }

    const serviceBoxes = document.querySelectorAll(".box-service");


    const chooseImageBtn = document.querySelector("#choose-image-btn");
    const fileInput = document.querySelector("#file-input");
    const selectedImage = document.querySelector("#selected-image");


    if (chooseImageBtn && fileInput && selectedImage) {
        chooseImageBtn.addEventListener("click", function () {
            fileInput.click();
        });

        fileInput.addEventListener("change", function () {
            const file = fileInput.files[0];

            if (file) {
                const reader = new FileReader();

                reader.onload = function (e) {
                    selectedImage.src = e.target.result;
                };

                reader.readAsDataURL(file);
            }
        });
    }





    const dropdownItems = document.querySelectorAll(".dropdown-item");
    const selectedService = document.getElementById("selected-service");

    if (dropdownItems.length > 0 && selectedService) {
        dropdownItems.forEach(item => {
            item.addEventListener("click", function (event) {
                event.preventDefault();
                selectedService.textContent = this.textContent;
            });
        });
    }
}

function resetDropdown() {
    const selectedService = document.getElementById("selected-service");
    
    const dropdownMenu = document.getElementById("dropdown-list");

    if (selectedService) {
        selectedService.textContent = "";
    }

    if (dropdownMenu) {
        dropdownMenu.classList.remove("show");
    }
}

const defaultImageSrc = "https://s3-alpha-sig.figma.com/img/7f22/f5d3/ef8248c3905d3c06a795c55711906557?Expires=1744588800&Key-Pair-Id=APKAQ4GOSFWCW27IBOMQ&Signature=sjmaH7PnE2rtPopdwj4WjwuMh7VhiaevLwS6LGEg~G5Cd2CiWZ3iNKePqgv1vY4GeCEydyG4Vf4vinhnTYKgOC87~5k3lvsZpR3AcyuVstmaFIag8Cylfd7yLCHS9QsLRjw9VhQ-yVAGt-KXFZWDZuoT~VzWyko617lK52pw29f~PHq0y0D7BOwKXJbMFgy1jUir14lI1-2SrLzS8kO1gu8R8hNFPyjvdBRsQe4emxx9YTSIVpY~K4bYg6MaFSU18ldWR5M4VDMvW1Q3gq-EPjZaC3wUCVdMcPiX7QnOfdK8asQczDg645Zsz4Ci9CMl860lRTB~FeEDsbbO~vpwZQ__";

function selectFirstServiceBox() {
    const serviceBoxes = document.querySelectorAll(".box-service");
    const displayService = document.querySelector(".display-service");
    const serviceNameInput = document.querySelector(".service-name input");
    const serviceDescriptionTextarea = document.querySelector(".service-description textarea");
    const selectedImage = document.querySelector("#selected-image");

    if (serviceBoxes.length > 0 && displayService && serviceNameInput && serviceDescriptionTextarea && selectedImage) {
        const firstServiceBox = serviceBoxes[0];
        const serviceName = firstServiceBox.querySelector("p").innerText;
        const serviceImage = firstServiceBox.querySelector("img").src;

        displayService.style.display = "block";
        serviceNameInput.value = serviceName;
        serviceDescriptionTextarea.value = serviceName;

        selectedImage.src = serviceImage;

        serviceBoxes.forEach(b => b.classList.remove("active"));
        firstServiceBox.classList.add("active");

        document.querySelectorAll("input[type='checkbox']").forEach(checkbox => {
            checkbox.checked = false;
        });
    }
}

document.addEventListener("DOMContentLoaded", function () {
    const mainContainer = document.querySelector('.display-container');

    const menuMainScreen = document.querySelector('.menu-main-screen');
    const menuServiceConfig = document.querySelector('.menu-service-configuration');



    initializePageEvents();


});

function loadModal() {
    initializeModalEvents(); // Chỉ cần khởi tạo sự kiện
}

$(document).on('click', '.custom-btn-1', function () {
    // $("#processModal").modal("show")
    showProcessModal(this);
});

async function showProcessModal(button) {
    const idCitizenService = button.dataset.id;

    if (!idCitizenService) {
        alert('Không tìm thấy ID');
        return;
    }

    try {
        const basePath = window.location.pathname.split('/dashboard')[0];
    
        const serviceCodeElement = document.querySelector('select[name="service_code"]');
        const citizenNameElement = document.querySelector('input[name="citizen_name"]');

        const serviceCode = serviceCodeElement ? serviceCodeElement.value : '';
        const citizenName = citizenNameElement ? citizenNameElement.value : '';


        const params = new URLSearchParams({
            service_code: serviceCode,
            citizen_name: citizenName,
        }).toString();

        // Gọi fetch với URL mới
        const response = await fetch(`${basePath}/dashboard/detail/citizen-service/${idCitizenService}?${params}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
            },
        });

        const data = await response.json();

        if (data.error) {
            alert('Không tìm thấy dữ liệu');
            return;
        }

        const modalTitle = `STT: ${data.sequence_number ?? ''} - ${data.name.toUpperCase()} (${data.phone}) - DV: ${data.service.toUpperCase()}`;
        document.getElementById('citizenServiceId').value = data.id;
        document.getElementById('processModalLabel').textContent = modalTitle;
        document.getElementById('citizenAddress').value = data.address;
        document.getElementById('status').value = data.status;

        const citizenServiceId = data.id;

        //update status to reviewed to support reader
        fetch(`${basePath}/dashboard/citizen-service/update-status?service_code=${serviceCode}&citizen_name=${citizenName}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector(
                        'meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    id: citizenServiceId,
                    status: 1
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {


                    document.querySelector(`#citizen-service-list`).innerHTML =
                        data.updatedView;

                    renderUtcTimes();

                } else {
                    console.error('Cập nhật thất bại');
                }
            })
            .catch(error => console.error('Lỗi:', error));


        // Đẩy dữ liệu vào Quill Editor #editor-process
        editorDone.root.innerHTML = data.citizen_note ?? '';

        const cancelButton = document.getElementById('cancelButton');
        cancelButton.onclick = async function (e) {
            e.preventDefault();

            const quillEditor = Quill.find(document.querySelector('#editor-done'));
            const citizenNoteContent = quillEditor.root.innerHTML;

            try {
                const response = await fetch(`${window.location.origin}${basePath}/dashboard/cancel-process/${idCitizenService}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        citizen_note: citizenNoteContent,
                    })
                });

                const result = await response.json();
                if (result.success) {
                    toastr.options = {
                        "positionClass": "toast-bottom-right",
                        "timeOut": "4000",
                        "closeButton": true,
                        "progressBar": true
                    };
                    toastr[result.alertType](result.message);

                    // Cập nhật lại danh sách
                    document.getElementById('citizen-service-list').innerHTML = result.updatedListHtml;

                    // Ẩn modal
                    const modalElement = document.getElementById('processModal');
                    const modalInstance = bootstrap.Modal.getInstance(modalElement);
                    modalInstance.hide();
                } else {
                    alert(result.message || 'Có lỗi xảy ra.');
                }
            } catch (error) {
                console.error(error);
                alert('Đã xảy ra lỗi khi gửi yêu cầu hủy.');
            }
        };

        const updateForm = document.getElementById('processForm');
        updateForm.action = `${window.location.origin}${basePath}/dashboard/update/citizen-service/${idCitizenService}`;

        // Hiển thị modal
        const modalElement = document.getElementById('processModal');
        const processModal = new bootstrap.Modal(modalElement, {
            backdrop: true,
            keyboard: true,
            focus: true,
        });

        modalElement.addEventListener('hidden.bs.modal', function () {
            button.focus();
        }, { once: true });



        processModal.show(); // Hiển thị modal
    } catch (err) {
        console.error('Lỗi khi gọi API:', err);
        alert('Lỗi khi tải dữ liệu từ máy chủ.');
    }
}

$(document).on('click', '.custom-btn-4', function () {
    showDoneModal(this);
});

async function showDoneModal(button) {
    const idCitizenService = button.dataset.id;

    if (!idCitizenService) {
        alert('Không tìm thấy ID');
        return;
    }

    try {
        const basePath = window.location.pathname.split('/dashboard')[0];
        const response = await fetch(`${basePath}/dashboard/detail/citizen-service/${idCitizenService}`);
        const data = await response.json();

        if (data.error) {
            alert('Không tìm thấy dữ liệu');
            return;
        }

        // Đổ dữ liệu vào modal
        const modalTitle = `STT: ${data.sequence_number ?? ''} - ${data.name.toUpperCase()} (${data.phone}) - DV: ${data.service.toUpperCase()}`;
        document.getElementById('citizenServiceId_done').value = data.id;
        document.getElementById('doneModalLabel').textContent = modalTitle;
        document.getElementById('citizenAddress_done').value = data.address;
        document.getElementById('status-complete').value = data.status;

        // Gán nội dung ghi chú vào Quill editor
        editorComplete.root.innerHTML = data.citizen_note ?? '';

        // const cancelButton = document.querySelector('.custom-cancel-btn');
        // if (cancelButton) {
        //     cancelButton.href = `${window.location.origin}${basePath}/dashboard/cancel-process/${idCitizenService}`;
        // }

        const doneForm = document.getElementById('doneForm');
        doneForm.action = `${window.location.origin}${basePath}/dashboard/update/citizen-service/${idCitizenService}`;

        // Hiển thị modal
        const modalElement = document.getElementById('doneModal');
        const doneModal = new bootstrap.Modal(modalElement, {
            backdrop: true,
            keyboard: true,
        });

        modalElement.addEventListener('hidden.bs.modal', function () {
            button.focus();
        }, { once: true });

        doneModal.show();
    } catch (err) {
        console.error('Lỗi khi gọi API:', err);
        alert('Lỗi khi tải dữ liệu từ máy chủ.');
    }
}




// Đóng hồ sơ
$(document).on('click', '.custom-btn-3', function () {
    showCloseModal(this);
});

async function showCloseModal(button) {
    const idCitizenService = button.dataset.id;

    if (!idCitizenService) {
        alert('Không tìm thấy ID');
        return;
    }

    try {
        const basePath = window.location.pathname.split('/dashboard')[0];
        const response = await fetch(`${basePath}/dashboard/detail/citizen-service/${idCitizenService}`);
        const data = await response.json();

        if (data.error) {
            alert('Không tìm thấy dữ liệu');
            return;
        }

        // Đổ dữ liệu vào modal
        const modalTitle = `STT: ${data.sequence_number ?? ''} - ${data.name.toUpperCase()} (${data.phone}) - DV: ${data.service.toUpperCase()}`;
        document.getElementById('closeModalLabel').textContent = modalTitle;
        document.getElementById('citizenAddress_close').value = data.address;
        document.getElementById('citizenServiceId_close').value = data.id;
        document.getElementById('status-close').value = data.status;

        // Gán nội dung ghi chú vào Quill editor
        editorClose.root.innerHTML = data.citizen_note ?? '';

        const cancelButton = document.querySelector('#btn-close');
        if (cancelButton) {
            cancelButton.href = `${window.location.origin}${basePath}/dashboard/cancel-process/${idCitizenService}`;
        }

        const closeForm = document.getElementById('closeForm');
        closeForm.action = `${window.location.origin}${basePath}/dashboard/update/citizen-service/${idCitizenService}`;

        // Hiển thị modal
        const modalElement = document.getElementById('closeModal');
        const closeModal = new bootstrap.Modal(modalElement, {
            backdrop: true,
            keyboard: true,
        });

        modalElement.addEventListener('hidden.bs.modal', function () {
            button.focus();
        }, { once: true });

        closeModal.show();
    } catch (err) {
        console.error('Lỗi khi gọi API:', err);
        alert('Lỗi khi tải dữ liệu từ máy chủ.');
    }
}

// Khi submit form thì lưu nội dung editor vào hidden input



$(document).on('click', '.custom-btn-2', function () {
    showCancelModal(this);
});

async function showCancelModal(button) {
    const idCitizenService = button.dataset.id;

    if (!idCitizenService) {
        alert('Không tìm thấy ID');
        return;
    }

    try {
        const basePath = window.location.pathname.split('/dashboard')[0];
        const response = await fetch(`${basePath}/dashboard/detail/citizen-service/${idCitizenService}`);
        const data = await response.json();

        if (data.error) {
            alert('Không tìm thấy dữ liệu');
            return;
        }

        // Gán dữ liệu vào modal
        document.getElementById('confirm-stt').textContent = data.sequence_number ?? '';
        document.getElementById('confirm-name').textContent = data.name ?? '';
        document.getElementById('confirm-phone').textContent = data.identity_number ?? '';
        document.getElementById('confirm-service').textContent = data.service ?? '';
        document.getElementById('confirm-location').textContent = data.address ?? '';

        // Gán id để xử lý sau
        document.querySelector('.yes-confirm').setAttribute('data-id', idCitizenService);

        // Hiển thị modal
        const modalElement = document.getElementById("confirmModal");
        const confirmModal = new bootstrap.Modal(modalElement, {
            backdrop: true,
            keyboard: true,
        });

        confirmModal.show();
    } catch (err) {
        console.error('Lỗi khi gọi API:', err);
        alert('Lỗi khi tải dữ liệu từ máy chủ.');
    }
}

// Xử lý nút "Đồng ý"
// document.body.addEventListener('click', function (event) {
//     if (event.target && event.target.classList.contains('yes-confirm')) {
//         const id = event.target.getAttribute('data-id');
//         if (id) {
//             const basePath = window.location.pathname.split('/dashboard')[0];
//             window.location.href = `${window.location.origin}${basePath}/dashboard/cancel-process/${id}`;
//         } else {
//             alert('Không tìm thấy ID để huỷ yêu cầu!');
//         }
//     }
// });




// Newss
// Dropdown for select newsgroup
document.addEventListener("DOMContentLoaded", function () {
    // Dropdown
    const dropdownMenu = document.getElementById("dropdown-list");

    if (dropdownMenu) {
        dropdownMenu.addEventListener("click", function (e) {
            const item = e.target.closest(".dropdown-item");
            if (item) {
                //   const selectedText = item.textContent;
                //   document.getElementById("selected-news").textContent = selectedText;
                document.getElementById("dropdownMenuButton").blur();
            }
        });
    }
});


// js for detail-content



const ITEMS_PER_PAGE = 10;
let currentPage = 1;
let selectedTitle = "";


function updateDividerLine() {
    setTimeout(() => {
        const container = document.getElementById("list-news-container");
        const divider = document.querySelector(".divider-line");

        if (container && divider) {
            const isOverflowing = container.scrollHeight > container.clientHeight;
            divider.style.display = isOverflowing ? "none" : "block";
        }
    }, 50);
}

updateDividerLine();

// hide divider and display scroll for role-management
function updateDivider2Line() {
    setTimeout(() => {
        const container = document.getElementById("list-content-container");
        const divider = document.querySelector(".divider-line");

        if (container && divider) {
            const isOverflowing = container.scrollHeight > container.clientHeight;
            divider.style.display = isOverflowing ? "none" : "block";
        }
    }, 50);
}
updateDivider2Line();

// Filter the news group footer
document.addEventListener("DOMContentLoaded", function () {
    const dropdownMenu = document.getElementById("dropdown-list-footer");

    if (dropdownMenu) {
        dropdownMenu.addEventListener("click", function (e) {
            const item = e.target.closest(".dropdown-item");
            if (item) {
                const selectedText = item.textContent;
                document.getElementById("footer-selected-news").textContent = selectedText;
                document.getElementById("dropdownMenuButton").blur();
            }
        });
    }
});

// select card-news and select image
document.addEventListener("DOMContentLoaded", function () {
    const chooseImageBtn = document.querySelector("#choose-image-btn-news");
    const fileInput = document.querySelector("#file-input");
    const selectedImage = document.querySelector("#selected-image-news");


    if (chooseImageBtn && fileInput && selectedImage) {
        chooseImageBtn.addEventListener("click", function (e) {
            e.preventDefault();
            fileInput.click();
        });

        fileInput.addEventListener("change", function () {
            const file = fileInput.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    selectedImage.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    }


});


// // Toast
function showToast(message = "Thông báo!", type = "success") {
    const toastEl = document.getElementById("customToast");
    const toastBody = toastEl.querySelector(".toast-body");
    const toastIcon = toastEl.querySelector(".toast-icon");

    // Reset icon & class
    toastEl.className = "toast toast-animated align-items-center border-0";
    toastEl.classList.add("text-white");

    switch (type) {
        case "success":
            toastEl.classList.add("bg-success");
            toastIcon.className = "toast-icon bi bi-check-circle-fill";
            break;
        case "error":
            toastEl.classList.add("bg-danger");
            toastIcon.className = "toast-icon bi bi-x-circle-fill";
            break;
        case "info":
            toastEl.classList.add("bg-info");
            toastIcon.className = "toast-icon bi bi-info-circle-fill";
            break;
        default:
            toastEl.classList.add("bg-secondary");
            toastIcon.className = "toast-icon bi bi-exclamation-circle-fill";
            break;
    }

    toastBody.textContent = message;

    const toast = new bootstrap.Toast(toastEl, {
        delay: 3000,
        autohide: true,
    });

    toast.show();
}


// $(document).ready(function () {
//     const postsPerPage = 4;
//     const $posts = $('.post-item');
//     const totalPosts = $posts.length;
//     const totalPages = Math.ceil(totalPosts / postsPerPage);

//     let currentPage = 1;

//     // Function to render posts by page
//     function showPage(page) {
//         currentPage = page;
//         const start = (page - 1) * postsPerPage;
//         const end = start + postsPerPage;

//         $posts.hide().slice(start, end).show();

//         $('#pagination .page-item').removeClass('active');

//         $('#page-1 a').text(1);
//         $('#page-last a').text(totalPages);
//         if (page === 1) {
//             $('#page-1').addClass('active');
//         } else if (page === totalPages) {
//             $('#page-last').addClass('active');
//         }

//         // Toggle dots
//         $('#dots').toggle(page > 2 && page < totalPages - 1);
//     }

//     // Prev / Next
//     $('#prev-button a').on('click', function (e) {
//         e.preventDefault();
//         if (currentPage > 1) {
//             showPage(currentPage - 1);
//         }
//     });

//     $('#next-button a').on('click', function (e) {
//         e.preventDefault();
//         if (currentPage < totalPages) {
//             showPage(currentPage + 1);
//         }
//     });

//     // Page number buttons
//     $('#page-1 a').on('click', function (e) {
//         e.preventDefault();
//         showPage(1);
//     });

//     $('#page-last a').on('click', function (e) {
//         e.preventDefault();
//         showPage(totalPages);
//     });

//     // Initial render
//     showPage(1);
// });





document.addEventListener("DOMContentLoaded", function () {
    const serviceContainer = document.querySelector(".service");
    const serviceContent = document.querySelector(".service .row");
    const verticalLine = document.querySelector(".vertical-line-service");

    if (serviceContent && serviceContainer && verticalLine) {
        const isOverflowing = serviceContent.scrollHeight > serviceContainer.clientHeight;
        verticalLine.style.display = isOverflowing ? "none" : "block";
    }
});

document.querySelectorAll('.news-checkbox').forEach(checkbox => {
    checkbox.addEventListener('click', function (e) {
        e.preventDefault(); // chặn click
    });
});

document.addEventListener("DOMContentLoaded", function () {
    const chooseImageBtn = document.querySelector("#choose-image-btn-news");
    const fileInput = document.querySelector("#file-input");
    const selectedImage = document.querySelector("#selected-image");


    if (chooseImageBtn && fileInput && selectedImage) {
        chooseImageBtn.addEventListener("click", function (e) {
            e.preventDefault();
            fileInput.click();
        });

        fileInput.addEventListener("change", function () {
            const file = fileInput.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    selectedImage.src = e.target.result;
                    selectedImage.classList.add('full-image');
                };
                reader.readAsDataURL(file);
            }
        });
    }


});

document.addEventListener("DOMContentLoaded", function () {
    const chooseImageBtn = document.querySelector("#choose-image-btn-post");
    const fileInput = document.querySelector("#file-input-post");
    const selectedImage = document.querySelector("#selected-image-post");


    if (chooseImageBtn && fileInput && selectedImage) {
        chooseImageBtn.addEventListener("click", function (e) {
            e.preventDefault();
            fileInput.click();
        });

        fileInput.addEventListener("change", function () {
            const file = fileInput.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    selectedImage.src = e.target.result;
                    selectedImage.classList.add('full-image');
                };
                reader.readAsDataURL(file);
            }
        });
    }


});


document.addEventListener('DOMContentLoaded', function () {
    const buttons = document.querySelectorAll('.custom-btn-1, .custom-btn-3');  // Chọn các nút action
    buttons.forEach(button => {
        button.addEventListener('click', function (event) {
            const action = event.target.getAttribute('data-action');  // Lấy tên action
            const idCitizenService = event.target.getAttribute('data-id');
            openModal(action, idCitizenService);
        });
    });
});

function openModal(action, idCitizenService) {
    const modal = new bootstrap.Modal(document.getElementById('actionModal'));
    modal.show();
    // Có thể thêm API call nếu cần để lấy thêm dữ liệu cho modal
}

const $datepicker = $('#datepicker');

$('#datepicker').datepicker({
    format: 'dd/mm/yyyy',
    autoclose: true,
    todayHighlight: true,
    language: 'vi',
    container: '.datepicker-wrapper'
});

$('#calendar-icon').on('click', function () {
    $datepicker.datepicker('show');
});


$(document).ready(function () {
    var $container = $('#list-news-container');
    var $activeItem = $container.find('.post-item.active');

    if ($activeItem.length) {
        $container.animate({
            scrollTop: $activeItem.position().top + $container.scrollTop()
        }, 500); // 500 ms duration
    }
});










