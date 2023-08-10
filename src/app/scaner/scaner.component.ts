
import { AfterViewInit, Component, ElementRef, EventEmitter, OnDestroy, OnInit, Output, ViewChild } from '@angular/core';
import { LazyLoadScriptService } from 'src/app/common/lazy-load-script-service';
import { BrowserMultiFormatReader } from '@zxing/library';

declare let ZXing: any;

@Component({
  selector: 'app-scaner',
  templateUrl: './scaner.component.html',
  styleUrls: ['./scaner.component.scss']
})





export class ScanerComponent implements OnInit, AfterViewInit, OnDestroy {
  @ViewChild('SourceSelect', { static: false }) SourceSelect?: ElementRef;
  @Output() newResult = new EventEmitter();


  codeReader: any;
  selectedDeviceId: any;
  results: any = []

  constructor(
    private lazyLoadService: LazyLoadScriptService
  ) {

  }
  ngOnInit() { }
  ngOnDestroy() {
    this.reset()
  }

  ngAfterViewInit() {

    this.codeReader = new BrowserMultiFormatReader()
    this.codeReader.getVideoInputDevices()
      .then((videoInputDevices: any) => {

        if (videoInputDevices.length >= 1) {
          videoInputDevices.forEach((element: any) => {
            const sourceOption = document.createElement('option')
            sourceOption.text = `${element.label}- ${element.deviceId} `
            sourceOption.value = element.deviceId
            this.SourceSelect?.nativeElement.appendChild(sourceOption)
          })
        }
        this.selectedDeviceId = videoInputDevices[videoInputDevices.length-1].deviceId
       // this.start()
      })

      .catch((err: any) => {
        console.error(err)
      })

    // this.lazyLoadService.loadScript('https://unpkg.com/@zxing/library@latest')
    // .subscribe(res=>{
    //   console.log(ZXing)
    //   this.codeReader = new ZXing.BrowserMultiFormatReader()
    // this.codeReader.listVideoInputDevices()
    //   .then((videoInputDevices: any) => {
    //     this.selectedDeviceId = videoInputDevices[0].deviceId
    //     if (videoInputDevices.length >= 1) {
    //       videoInputDevices.forEach((element: any) => {
    //         const sourceOption = document.createElement('option')
    //         sourceOption.text = element.label
    //         sourceOption.value = element.deviceId
    //         this.SourceSelect?.nativeElement.appendChild(sourceOption)
    //       })
    //     }
    //     this.start()
    //   })
    //   .catch((err: any) => {
    //     console.error(err)
    //   })
    // })
  }
  start() {
    this.codeReader.decodeFromVideoDevice(this.selectedDeviceId, 'video', (result: any, err: any) => {

      if (result) {
        if (window.navigator.vibrate) {
          window.navigator.vibrate(400)
        }
        this.newResult.emit({ result: result.text, success: true });
        this.reset()
      }
      if (err && !(err instanceof ZXing.NotFoundException)) {
        console.log(err, "err")
        this.newResult.emit({ result: err, success: false });

      }
    })
  }

  reset() {
    this.codeReader.reset()
    this.results = []
  }
  SourceChange(e: any) {
    //this.reset()
    this.selectedDeviceId = this.SourceSelect?.nativeElement.value;
   // this.start()
  }
}
