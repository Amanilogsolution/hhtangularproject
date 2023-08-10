import { Injectable } from '@angular/core';
import { HttpErrorResponse, HttpEvent, HttpHandler, HttpHeaders, HttpInterceptor, HttpParams, HttpRequest, HttpResponse } from "@angular/common/http"
import { AuthService } from './auth.service';
import { exhaustMap, take, tap } from 'rxjs/operators';
import { CommonService } from '../common/common.service';
import { Observable } from 'rxjs';

@Injectable()
export class AuthInterceptorService implements HttpInterceptor {

    constructor(
        private authService: AuthService,
        private commoService: CommonService
    ) { }


    intercept(req: HttpRequest<any>, next: HttpHandler):Observable<HttpEvent<any>> {
        this.commoService.requestStarted();


        return this.handle(next, req)

    }
    handle(next:HttpHandler, req: HttpRequest<any>){
        return this.authService.user.pipe(take(1), exhaustMap(user => {
            const token = user && user.token ? user.token : null;
            let modifiedReq ;
            if(req.url.includes("file-upload.php")){
                modifiedReq = req.clone({
                    headers: new HttpHeaders({
                      'Authorization': `Bearer ${token}`
                    })
                  });
            }else{
                modifiedReq = req.clone({
                    headers: new HttpHeaders({
                      'Content-Type':  'application/json',
                      'Authorization': `Bearer ${token}`
                    })
                  });
            }

            return next.handle(modifiedReq).pipe(tap(event=>{
                if(event instanceof HttpResponse){
                    this.commoService.requestEnded()
                }

            },(error:HttpErrorResponse)=>{
                this.commoService.requestEnded()
                throw error;

            }))
        }))



    }
}