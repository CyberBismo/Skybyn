function hideSidePanels() {
    const lp = document.getElementById('left-panel');
    const rp = document.getElementById('right-panel');
    if (window.innerWidth < 1240) {
        lp.style.transform = "translate(-300px)";
        rp.style.transform = "translate(300px)";
    } else {
        lp.style.transform = "translate(0px)";
        rp.style.transform = "translate(0px)";
    }
}
hideSidePanels();
window.addEventListener('resize', hideSidePanels);


