# Lost and Found UPNVJ FIK
Final project for Web Programming — Information Systems, UPNVJ.
An application for tracking students lost item and gave back their lost item.
## Requirements
- PHP 8.3 or newer
- Composer
- MySQL (XAMPP)
## Running it from scratch
1. `git clone https://github.com/15naufalghani-max/Lost-and-Found.git`
2. `cd Lost-and-Found`
3. `composer install`
4. `npm install` lalu `npm run build`
5. `copy .env.example .env`
6. Buat database kosong `lostfound_fik` di phpMyAdmin
7. `php artisan key:generate`
8. `php artisan migrate:fresh --seed`
9. `php artisan storage:link`
10. `php artisan serve`, lalu buka http://127.0.0.1:8000
## Division of work
| Name 				            | Student no. | Part built 				                     |
|-------------------------------|-------------|----------------------------------------------|
| Firyal Humaira Asyaffa 	    | 2510512082  | Frontend Developer (CRUD)                    |
| Kenzia Zahwa Eddyputri 	    | 2510512121  | UI/UX Designer & Frontend Lead 		         |
| Kenny Tana Haholongan Pandia  | 2510512027  | Backend Developer (System & Database) 	     |
| Muhammad Naufal Ghani 	    | 2510512072  | Backend Developer (Claim & Verification)     |
| Tracy Gabriella Donda Sitorus | 2510512122  | Frontend Developer (Search & Interaction)    |
| Vrista Darin Nadira	        | 2510512119  | Frontend Developer (User & Claim Management) |

