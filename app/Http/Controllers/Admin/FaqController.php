<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FaqRequest;
use App\Models\Faq;

class FaqController extends Controller
{
    public function index()
    {
        return view('admin.faqs.index', ['faqs' => Faq::orderBy('sort_order')->paginate(15)]);
    }

    public function create()
    {
        return view('admin.faqs.form', ['faq' => new Faq(['is_active' => true, 'sort_order' => 0])]);
    }

    public function store(FaqRequest $request)
    {
        $data = $request->validated();
        $data['sort_order'] ??= 0;
        Faq::create($data);

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ ditambahkan.');
    }

    public function edit(Faq $faq)
    {
        return view('admin.faqs.form', compact('faq'));
    }

    public function update(FaqRequest $request, Faq $faq)
    {
        $data = $request->validated();
        $data['sort_order'] ??= 0;
        $faq->update($data);

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ diperbarui.');
    }

    public function destroy(Faq $faq)
    {
        $faq->delete();

        return back()->with('success', 'FAQ dihapus.');
    }
}
