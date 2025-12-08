import { Component } from '@angular/core';
import { FormsModule, NgForm } from '@angular/forms';
import { CommonModule } from '@angular/common';
import { ButtonModule } from 'primeng/button';
import { ContactService } from '../contact.service';

@Component({
  selector: 'app-contact-page',
  standalone: true,
  imports: [FormsModule, CommonModule, ButtonModule],
  templateUrl: './contact-page.component.html',
  styleUrl: './contact-page.component.css',
})

export class ContactPageComponent {
  email: string = '';
  message: string = '';
  isSent = false;
  isError = false;

  constructor(private contactService: ContactService) { }
  sendMessage(form: NgForm) {
    if (form.invalid) return;

    console.log('Email envoyé :', this.email);
    console.log('Message :', this.message);

    this.contactService.sendMessage({
      email: this.email,
      message: this.message
    }).subscribe({
      next: (response) => {
        this.isSent = true;
        this.isError = false;
        form.resetForm();

        setTimeout(() => (this.isSent = false), 3000);
      },
      error: (err) => {
        console.error(err);
        this.isError = true;

        setTimeout(() => (this.isError = false), 3000);
      }
    });
  }
}



