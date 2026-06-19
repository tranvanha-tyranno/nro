// Số lượng ảnh GIF tối đa cho phép mỗi lần xuất hiện
var maxGIFs = 5; 
// Thời gian chờ xuất hiện ảnh GIF mới (ms)
var gifAppearInterval = 3000;
// Thời gian mỗi ảnh GIF tồn tại (ms)
var gifDisplayTime = 5000; 
// Đường dẫn ảnh GIF
var gifSrc = "/Assets/Images/2 - Copy.gif"; 

// Hàm tạo ảnh GIF tại vị trí ngẫu nhiên và xóa sau 5 giây
function createGIFs() {
    // Xóa các ảnh GIF cũ trước khi tạo mới
    removeGIFs();

    // Số lượng ảnh GIF ngẫu nhiên cho mỗi lần xuất hiện (từ 1 đến maxGIFs)
    var numGIFs = Math.floor(Math.random() * maxGIFs) + 1;

    // Tạo và hiển thị số lượng ảnh GIF ngẫu nhiên
    for (var i = 0; i < numGIFs; i++) {
        var gif = document.createElement('img');
        gif.src = gifSrc;
        gif.classList.add('static-gif');
        gif.style.position = 'fixed';
        gif.style.width = '60px'; // Giữ nguyên kích thước
        gif.style.height = '60px';
        gif.style.pointerEvents = 'none';

        // Đặt vị trí ngẫu nhiên trên màn hình
        gif.style.left = Math.random() * (window.innerWidth - 60) + 'px';
        gif.style.top = Math.random() * (window.innerHeight - 60) + 'px';

        // Thêm ảnh GIF vào body
        document.body.appendChild(gif);
    }

    // Sau 5 giây, xóa tất cả ảnh GIF hiện tại
    setTimeout(removeGIFs, gifDisplayTime);
}

// Hàm xóa tất cả các ảnh GIF hiện tại trên trang
function removeGIFs() {
    var gifs = document.querySelectorAll('.static-gif');
    gifs.forEach(function(gif) {
        gif.parentNode.removeChild(gif);
    });
}

// Lặp lại việc tạo ảnh GIF mỗi 8 giây (3 giây chờ + 5 giây hiển thị)
setInterval(createGIFs, gifAppearInterval + gifDisplayTime);
