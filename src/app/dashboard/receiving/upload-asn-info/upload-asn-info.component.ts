
import { Location } from '@angular/common';
import { Component, OnInit } from '@angular/core';
import { NgForm } from '@angular/forms';
import { DomSanitizer, SafeUrl } from '@angular/platform-browser';
import { ActivatedRoute } from '@angular/router';
import { CommonService } from 'src/app/common/common.service';
import { AuthService } from 'src/app/login/auth.service';
import { ReceivingService } from '../receiving.service';

@Component({
  selector: 'app-upload-asn-info',
  templateUrl: './upload-asn-info.component.html',
  styleUrls: ['./upload-asn-info.component.scss']
})
export class UploadASNInfoComponent implements OnInit {
  files: any[] = []
  asn: string = "";
  noASN: boolean = false;
  user:any;



  constructor(
    private sanitizer: DomSanitizer,
    private commonService: CommonService,
    private receivingService: ReceivingService,
    private route: ActivatedRoute,
    private location: Location,
    private authService: AuthService
  ) {

    this.route.params.subscribe(res => {
      this.asn = res.asn
      this.findASN()
    })
  }
  findASN() {
    this.receivingService.findASN(this.asn).subscribe((res: any) => {
      this.noASN = res.asnlist.records.length === 0
    })
  }

  ngOnInit(): void {

    this.authService.user.subscribe(user=>{

      this.user = user?.userId;
    }).unsubscribe()

  }
  sanitizeImageUrl(imageUrl: string): SafeUrl {
    return this.sanitizer.bypassSecurityTrustResourceUrl(imageUrl);
  }
  selectFile(e: any, index: number) {


    this.files[index] = {
      file: e.target.files[0],
      imgName: e.target.value.split("\\").pop(),
      imgFakepath: (window.URL ? window.URL : window.webkitURL).createObjectURL(e.target.files[0]),
      filedName: e.target.name
    }
    e.target.parentElement.lastChild.innerText = "Change";
  }
  async updateData(form: NgForm) {
    if (!this.files.length) {
      alert("Please upload some images")
      return
    }
    if (!form.valid || !this.files) {
      var r = confirm("You are not uploading all images. Are you sure?");
      if (r == true) {
      } else {
        return
      }

    }
    let data: any = {};

    await Promise.all(this.files.map(async (item) => {
      let folderName = `SWIM/INWARD/ASN/${this.asn}`
      let fileName =  `${new Date().getTime()}-${item.file.name}`;
      data[item.filedName] = folderName+"/"+fileName;;
      this.commonService.uploadFiles(item.file, folderName, fileName).subscribe(res=>{
        console.log(res)
      })
    }));


    // this.files.map((item) => {
    //   let folderName = `SWIM/INWARD/ASN/${this.asn}`
    //   let fileName =  `${folderName}/${new Date().getTime()}-${item.file.name}`;
    //   data[item.filedName] = fileName;
    //   this.commonService.uploadFiles(item.file, folderName, fileName).subscribe(res=>{
    //     console.log(res)
    //   })
    // })
    this.receivingService.uploadASN(data, this.asn, this.user).subscribe((res: any) => {

      if (res.status === 1) {
        console.log("upload")
        alert(res.messsage);
        this.location.back();
      } else {
        alert("Some thing went wrong, files could not be uploaded")
      }


    }, err => alert("Some thing went wrong, files could not be uploaded"))

  }

}
