import { Component, OnInit, Input } from '@angular/core';

@Component({
  selector: 'app-connection-status',
  templateUrl: './connection-status.component.html',
  styleUrls: ['./connection-status.component.scss']
})
export class ConnectionStatusComponent implements OnInit {
  @Input() onlineStatusMessage?: string;
  @Input() onlineStatus?: string;

  constructor() { }

  ngOnInit(): void {
  }

}
