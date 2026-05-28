@extends('layout.design')

@section('title')
    Students Page
@endsection

@section('Header')
    @parent
@endsection

@section('Content')
    <h1 class="page-title">Student Records</h1>
    <p class="page-subtitle">
        This page displays the official list of students together with their age,
        course, and current year level classification.
    </p>

    <div class="card">
        <div class="card-header">
            <div class="card-title">Students List</div>
            <div class="card-subtitle">Academic information and student classification overview</div>
        </div>

        <div class="card-body">
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th class="text-center">No.</th>
                            <th>Student Name</th>
                            <th class="text-center">Age</th>
                            <th>Course</th>
                            <th>Year Level</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $student)
                            @php
                                if($student['age'] == 19){
                                    $yearLevel = 'Students';
                                    $badgeClass = 'badge-purple';
                                } elseif($student['age'] == 20){
                                    $yearLevel = 'Sophomore';
                                    $badgeClass = 'badge-green';
                                } elseif($student['age'] == 21){
                                    $yearLevel = 'Junior';
                                    $badgeClass = 'badge-yellow';
                                } elseif($student['age'] == 22){
                                    $yearLevel = 'Senior';
                                    $badgeClass = 'badge-blue';
                                } else {
                                    $yearLevel = 'Irregular';
                                    $badgeClass = 'badge-red';
                                }
                            @endphp

                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $student['student_name'] }}</td>
                                <td class="text-center">{{ $student['age'] }}</td>
                                <td>{{ $student['course'] }}</td>
                                <td>
                                    <span class="badge {{ $badgeClass }}">{{ $yearLevel }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="empty-state">There are no students in the data resource.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('Footer')
    @parent
@endsection