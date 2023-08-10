import { HttpClient, HttpErrorResponse } from '@angular/common/http';
import { Injectable } from "@angular/core"
import { ActivatedRoute, Router } from '@angular/router';
import { BehaviorSubject, Subject, throwError } from 'rxjs';
import { catchError, tap } from "rxjs/operators"
import { environment } from 'src/environments/environment';
import { CommonService } from '../common/common.service';
import { User } from './user.modal';



interface AuthResponseData {
    userName: string,
    userId: string,
    token: string,
    tokenExpiresIn: Date,
    warehouseLocation: any

}
@Injectable({
    providedIn: "root"
})
export class AuthService {
    private tokenExpirationTimer: any;
    user = new BehaviorSubject<User | null>(null);
    apiUrl = environment.api;

    constructor(
        private http: HttpClient,
        private router: Router,
        private route: ActivatedRoute,
        private commonService: CommonService
    ) {

    }
    login(userData: any) {
        return this.http.post<AuthResponseData>(`${this.apiUrl}/login.php`, userData)
            .pipe(
                catchError(this.handleError),
                tap(resData => {
                    let expiryTimeFromNow = 30 * 24 * 60 * 60;
                    this.handleAuth(resData.userName, userData.userId, resData.token, expiryTimeFromNow, resData.warehouseLocation)
                }
                ))
    }
    private handleError(errorRes: HttpErrorResponse) {
        let errorMessage = 'An unknown error occurred!';
        console.log(errorRes.error.message)
        if (errorRes.error.message) {
            errorMessage = errorRes.error.message;
        }
        if (!errorRes.error || !errorRes.error.error) {
            return throwError(errorMessage);
        }

        return throwError(errorMessage);
    }
    private handleAuth(userName: string, userId: string, token: string, expiresIn: number, warehouseLocation: any) {

        const tokenExpirationDate = new Date(new Date().getTime() + expiresIn * 1000);
        console.log(expiresIn, tokenExpirationDate)
        const user = new User(userName, userId, token, tokenExpirationDate, warehouseLocation)
        this.user.next(user);
        this.autoLogout(expiresIn);
        localStorage.setItem('userData', JSON.stringify(user));
    }
    logout() {
        this.http.post(`${this.apiUrl}/logout.php`, {}).subscribe(res => {
            console.log(res)
        });
        this.user.next(null);
        this.router.navigate(['/login']);
        localStorage.removeItem('userData');
        if (this.tokenExpirationTimer) {
            clearTimeout(this.tokenExpirationTimer);
        }
        this.tokenExpirationTimer = null;
        this.commonService.setUserLocation('')
    }
    autoLogin() {
        const localData: any = localStorage.getItem('userData');
        const userData: {
            userId: string;
            userName: string;
            id: string;
            _token: string;
            _tokenExpirationDate: string;
            warehouseLocation: any
        } | null = JSON.parse(localData);
        if (!userData) {
            return;
        }

        const loadedUser = new User(
            userData.userName,
            userData.userId,
            userData._token,
            new Date(userData._tokenExpirationDate),
            userData.warehouseLocation
        );

        if (loadedUser.token) {
            this.user.next(loadedUser);
           // if (window.location.pathname === "/index.html" || window.location.pathname === `/` || window.location.href.includes("login")) {
                this.router.navigate(['dashboard', "summary"])
            //}

            const expirationDuration =
                new Date(userData._tokenExpirationDate).getTime() -
                new Date().getTime();
            this.autoLogout(expirationDuration / 1000);
        }
    }
    autoLogout(expirationDuration: number) {
        this.tokenExpirationTimer = setTimeout(() => {
            this.logout();
        }, expirationDuration);
    }
}