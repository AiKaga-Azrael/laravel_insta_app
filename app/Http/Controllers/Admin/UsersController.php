<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UsersController extends Controller
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    # index() - view the Admin: users page
    public function index()
    {
        //wtihTrashed() - Include the soft deleted records in a query's result
        $all_users = $this->user->withTrashed()->latest()->paginate(5);

        return view('admin.users.index')
                ->with('all_users', $all_users);
    }

    # deactivate() - to soft delete the user
    public function deactivate($id)
    {
        $this->user->destroy($id);

        return redirect()->back();
    }

    # activate() undelete SofDeletes column(deleted_at) back to NULL
    public function activate($id)
    {
        // onlyTrashed() - retrieves soft deleted records only.
        // restore() - This will "un-delete" a soft deleted model. This will set the "deleted_at" column to null
        $this->user->onlyTrashed()->findOrFail($id)->restore();

        return redirect()->back();
    }

    # search
    public function search(Request $request)
    {
        $all_users = $this->user->withTrashed()
            ->where('name', 'like', '%' . $request->search . '%')
            ->paginate(4);

        return view('admin.users.search')
            ->with('all_users', $all_users)
            ->with('search', $request->search);
    }
}
