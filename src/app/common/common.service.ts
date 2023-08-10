import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { BehaviorSubject, Observable, Subject } from 'rxjs';
import { environment } from 'src/environments/environment';
@Injectable({
  providedIn: 'root'
})
export class CommonService {
  file_data: any = '';
  apiUrl = environment.api;
  menuToggle = new Subject()
  selectedWarehouse = new BehaviorSubject("k")
  private count = 0;
  private spinner$ = new BehaviorSubject<string>('');

  constructor(
    private http: HttpClient
  ) { }
  toggleSideNav() {

    this.menuToggle.next()
  }
  setUserLocation(whereHouse: any) {
    this.selectedWarehouse.next(whereHouse)
  }

  getSpinnerObserver(): Observable<string> {
    return this.spinner$.asObservable();
  }

  requestStarted() {
    if (++this.count === 1) {
      this.spinner$.next('start');
    }
  }

  requestEnded() {
    if (this.count === 0 || --this.count === 0) {
      this.spinner$.next('stop');
    }
  }

  resetSpinner() {
    this.count = 0;
    this.spinner$.next('stop');
  }

  uploadFiles(inputFile: any, folderName: string, fileName: string, containerName: string = "wmslocker"): Observable<any> {

    const fd = new FormData()
    fd.append('image', inputFile)
    fd.append('folderName', folderName)
    fd.append('fileName', fileName)
    fd.append('containerName', containerName)
    return this.http.post(`https://filemanager.awlindia.com/file-upload.php`, fd, {
      reportProgress: true,
      observe: "events"
    })
  }
}
