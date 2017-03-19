class OrderProgressBar {
  constructor(percent) {
    this.percent = percent;
  }

  load() {
    this.setProgressBar();
  }

  setProgressBar() {
    $('.order-progress-bar > .status').css('width',this.percent+'%')
  }

}