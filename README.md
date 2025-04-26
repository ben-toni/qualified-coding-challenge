# Item Manager Challenge

## How to Run Locally

1. Clone the repo
2. Run `composer install`
3. Copy `.env.example` to `.env` and configure database
4. Run `php artisan migrate`
5. Run `php artisan db:seed`
6. Run `npm install && npm run dev`
7. Serve with `php artisan serve`

## Decisions Made
- Used Tiptap for WYSIWYG editor because it's Vue 3 native.
- Used cursor pagination for efficient infinite scroll.

## Improvements with More Time
- Add update item features (it was something I was working on before hitting 2h, so I stopped)
- Add service for the item model (move logic from controller to them)
- Update validation for create item (use request classes)
- Add proper testing cases for item functionality
- Add full mobile responsiveness and update the style to match the Qualified design
