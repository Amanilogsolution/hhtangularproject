import { Component, OnInit } from '@angular/core';
import { NgForm } from '@angular/forms';
import { Router } from '@angular/router';
import { AuthService } from './auth.service';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.scss']
})
export class LoginComponent implements OnInit {
  passwordVisible:boolean=false;
  _pw:string="password";
  loginError:any=null;


  constructor(private router: Router,
    private authService:AuthService) { }

  ngOnInit(): void {
  }
  login(form:NgForm){
    if(!form.valid){
      return;
    }

    this.authService.login(form.value).subscribe(res=>{
      this.router.navigate(["dashboard", "summary"])
    },err=>{
      console.log(err)
      this.loginError= err;
    })

  }
  togglePWVisiblity(){
    this.passwordVisible = !this.passwordVisible
    if(this._pw==="password"){
      this._pw="text"
    }else{
      this._pw="password"
    }

  }
}
