<?php
/** Halaman statis: ERD (relasi data inventaris). */
class PageController extends Controller
{
    public function erd(): void
    {
        $this->view('pages/erd', [], 'Relasi Data (ERD)');
    }
}
