<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Manager</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @vite('resources/css/app.css', 'resources/js/app.js')
</head>
<body>

    <div class="max-w-[900px] m-auto p-[20px] bg-slate-100 rounded-sm">
        <h1 class="text-3xl font-bold mb-6 text-center">Task Manager</h1>

        <!-- Button to toggle create/edit task form -->
        <button onclick="toggleForm()" class="bg-blue-500 text-white px-4 py-2 rounded mb-4">
            Create Task
        </button>

        <!-- Task form -->
        <div id="taskFormContainer" class="hidden mb-4 bg-white p-4 rounded shadow-md">
            <form id="taskForm" action="{{ route('tasks.store') }}" method="POST" class="flex mb-5 flex-col">
                @csrf
                <input type="hidden" id="methodField" name="_method" value="POST">

                <div class="mb-4">
                    <label for="title" class="block text-gray-700">Task Title</label>
                    <input type="text" name="title" id="title" class="w-full p-2 border border-gray-300 rounded mt-2" required>
                </div>
                <div class="mb-4">
                    <label for="description" class="block text-gray-700">Description</label>
                    <textarea name="description" id="description" class="w-full p-2 border border-gray-300 rounded mt-2"></textarea>
                </div>
                <div class="mb-4">
                    <label for="is_completed" class="flex items-center">
                        <input type="checkbox" name="is_completed" id="is_completed" class="mr-2">
                        Completed
                    </label>
                </div>
                <div class="flex justify-between items-center">
                    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Save Task</button>
                    <button type="button" onclick="toggleForm()" class="text-red-500">Cancel</button>
                </div>
            </form>
        </div>

        <!-- Task List -->
<!-- Task List -->
<ul id="taskList" class="bg-white p-4 rounded shadow-md">
    @foreach ($tasks as $task)
        <li id="task-{{ $task->id }}" class="py-4 border-b border-gray-300">
            <div class="flex justify-between items-center">
                <div>
                    <!-- Apply strikethrough if the task is completed -->
                    <h3 class="font-bold text-lg {{ $task->is_completed ? 'line-through text-gray-500' : '' }}">
                        {{ $task->title }}
                    </h3>
                    <p class="text-gray-600">{{ $task->description }}</p>
                </div>
                <div class="flex space-x-4">
                    <button 
                        onclick="editTask({{ $task->id }}, '{{ $task->title }}', '{{ $task->description }}', {{ $task->is_completed ? 'true' : 'false' }})" 
                        class="text-blue-500"
                    >
                        Edit
                    </button>
                    <form action="{{ route('tasks.destroy', $task->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded">Delete</button>
                    </form>
                </div>
            </div>
        </li>
    @endforeach
</ul>



    </div>

    <script>
 function toggleForm(isCreating = false) {
    const formContainer = document.getElementById('taskFormContainer');
    const taskList = document.getElementById('taskList');

    // Toggle visibility of the form
    formContainer.classList.toggle('hidden');

    // Toggle visibility of the task list
    if (!formContainer.classList.contains('hidden')) {
        taskList.classList.add('hidden'); // Hide the task list when the form is shown

        // If toggling for a new task, reset the form
        if (isCreating) {
            resetForm();
        }
    } else {
        taskList.classList.remove('hidden'); // Show the task list when the form is hidden
    }
}

function resetForm() {
    // Reset form fields
    const form = document.getElementById('taskForm');
    form.action = "{{ route('tasks.store') }}";
    document.getElementById('methodField').value = 'POST';
    document.getElementById('title').value = '';
    document.getElementById('description').value = '';
    document.getElementById('is_completed').checked = false;
}

function editTask(id, title, description, isCompleted) {
    const formContainer = document.getElementById('taskFormContainer');

    // Show the form only if it's hidden
    if (formContainer.classList.contains('hidden')) {
        toggleForm();
    }

    // Set the form action and method for editing
    const form = document.getElementById('taskForm');
    form.action = `/tasks/${id}`;
    document.getElementById('methodField').value = 'PUT';

    // Populate the form fields
    document.getElementById('title').value = title;
    document.getElementById('description').value = description;
    document.getElementById('is_completed').checked = isCompleted;
}

    </script>

</body>
</html>
