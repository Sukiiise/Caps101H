<x-adminlayout>
    <div class="container-fluid px-4">
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Schools Management
                            <a href="{{ route('admin.schools.create') }}" class="btn btn-primary float-end">Add School</a>
                        </h4>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Address</th>
                                    <th>Contact</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($schools as $school)
                                    <tr>
                                        <td>{{ $school->name }}</td>
                                        <td>{{ $school->address }}</td>
                                        <td>{{ $school->contact_number }}</td>
                                        <td>
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#assignUserModal{{ $school->id }}">
                                                Assign User
                                            </button>
                                        </td>
                                    </tr>

                                    <!-- Assign User Modal -->
                                    <div class="modal fade" id="assignUserModal{{ $school->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Assign User to {{ $school->name }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form action="{{ route('admin.schools.assign-user', $school) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label for="user_id" class="form-label">Select User</label>
                                                            <select name="user_id" id="user_id" class="form-control" required>
                                                                <option value="">Select a user</option>
                                                                @foreach(\App\Models\User::where('role', 'user')->get() as $user)
                                                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-primary">Assign User</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-adminlayout>
