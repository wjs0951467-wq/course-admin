if (document.getElementById('studentAdded')) {
    alert('학생 등록이 완료되었습니다.');
}

document.getElementById('confirmButton')?.addEventListener('click', function () {
    confirm('정말 이 작업을 진행하시겠습니까?');
});

document.getElementById('searchButton')?.addEventListener('click', function () {
    const keyword = document.getElementById('keyword').value;
    alert(`검색어: ${keyword}`);
});