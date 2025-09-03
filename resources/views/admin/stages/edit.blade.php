{{-- resources/views/admin/stages/edit.blade.php --}}
<x-app-layout>
  <x-slot name="header">
    <div class="form-header-container">
      <div class="header-flex">
        <div class="header-left">
          <div class="form-icon-wrapper"><i class="fas fa-edit"></i></div>
          <div class="ml-4">
            <h2 class="form-title">Edit Stage</h2>
            <p class="form-subtitle">Modify stage configuration and settings</p>
          </div>
        </div>
      </div>
    </div>
  </x-slot>

  @php
    $pre  = \App\Models\Assessment::where('stage_id', $stage->id)->where('type', 'pre')->first();
    $post = \App\Models\Assessment::where('stage_id', $stage->id)->where('type', 'post')->first();
  @endphp

  <div class="form-container">

    {{-- flash messages --}}
    @if(session('success'))
      <div class="flash success">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
      </div>
    @endif
    @if(session('error'))
      <div class="flash error">
        <i class="fas fa-triangle-exclamation"></i> {{ session('error') }}
      </div>
    @endif

    {{-- Assessments panel --}}
    <div class="assessments-card">
      <div class="assessments-header">
        <h3 class="card-title">Assessments for this Stage</h3>
        <p class="card-description">Each stage can have one pre-assessment and one post-assessment.</p>
      </div>

      <div class="assessments-grid">
        {{-- Pre-assessment tile --}}
        <div class="assess-tile">
          <div class="tile-left">
            <div class="tile-icon pre"><i class="fas fa-sign-in-alt"></i></div>
            <div>
              <div class="tile-title">Pre-assessment</div>
              @if($pre)
                <div class="tile-sub">Title: <strong>{{ $pre->title }}</strong></div>
              @else
                <div class="tile-sub muted">No pre-assessment yet.</div>
              @endif
            </div>
          </div>
          <div class="tile-actions">
            @if($pre)
              <a class="btn-primary" href="{{ route('admin.stages.assessments.edit', [$stage, $pre]) }}">
                <i class="fas fa-pen"></i><span>Edit</span>
              </a>

              {{-- DELETE (with confirm) --}}
              <form method="POST" action="{{ route('admin.stages.assessments.destroy', [$stage, $pre]) }}" class="inline js-delete-assessment">
                @csrf @method('DELETE')
                <button type="submit" class="btn-danger">
                  <i class="fas fa-trash"></i><span>Delete</span>
                </button>
              </form>
            @else
              <a class="btn-primary" href="{{ route('admin.stages.assessments.create', ['stage' => $stage->id, 'type' => 'pre']) }}">
                <i class="fas fa-plus"></i><span>Add</span>
              </a>
            @endif
          </div>
        </div>

        {{-- Post-assessment tile --}}
        <div class="assess-tile">
          <div class="tile-left">
            <div class="tile-icon post"><i class="fas fa-flag-checkered"></i></div>
            <div>
              <div class="tile-title">Post-assessment</div>
              @if($post)
                <div class="tile-sub">Title: <strong>{{ $post->title }}</strong></div>
              @else
                <div class="tile-sub muted">No post-assessment yet.</div>
              @endif
            </div>
          </div>
          <div class="tile-actions">
            @if($post)
              <a class="btn-primary" href="{{ route('admin.stages.assessments.edit', [$stage, $post]) }}">
                <i class="fas fa-pen"></i><span>Edit</span>
              </a>

              {{-- DELETE (with confirm) --}}
              <form method="POST" action="{{ route('admin.stages.assessments.destroy', [$stage, $post]) }}" class="inline js-delete-assessment">
                @csrf @method('DELETE')
                <button type="submit" class="btn-danger">
                  <i class="fas fa-trash"></i><span>Delete</span>
                </button>
              </form>
            @else
              <a class="btn-primary" href="{{ route('admin.stages.assessments.create', ['stage' => $stage->id, 'type' => 'post']) }}">
                <i class="fas fa-plus"></i><span>Add</span>
              </a>
            @endif
          </div>
        </div>
      </div>
    </div>

    {{-- Stage form --}}
    <div class="form-card">
      <div class="form-card-header">
        <h3 class="card-title">Stage Information</h3>
        <p class="card-description">Update the details for this learning stage</p>
      </div>

      <form method="POST" action="{{ route('admin.stages.update', $stage) }}" class="stage-form">
        @csrf @method('PUT')

        <div class="form-group">
          <label class="form-label">Stage Slug</label>
          <input type="text" name="slug" class="form-input" value="{{ $stage->slug }}" required>
          <div class="form-help">URL-friendly identifier for this stage</div>
        </div>

        <div class="form-group">
          <label class="form-label">Stage Title</label>
          <input type="text" name="title" class="form-input" value="{{ $stage->title }}" required>
          <div class="form-help">Display name for this stage</div>
        </div>

        <div class="form-group">
          <label class="form-label">Description</label>
          <textarea name="description" class="form-textarea" rows="4">{{ $stage->description }}</textarea>
          <div class="form-help">Brief description of what learners will accomplish</div>
        </div>

        <div class="form-group">
          <label class="form-label">Display Order</label>
          <input type="number" name="display_order" class="form-input" value="{{ $stage->display_order }}">
          <div class="form-help">Position in the stage sequence (1, 2, 3...)</div>
        </div>

        <div class="form-actions">
          <button type="submit" class="btn-primary">
            <i class="fas fa-save"></i><span>Update Stage</span>
          </button>
          <a href="{{ route('admin.stages.index') }}" class="btn-secondary">
            <i class="fas fa-times"></i><span>Cancel</span>
          </a>
        </div>
      </form>
    </div>
  </div>

  <script>
    // Confirm before deleting an assessment
    document.addEventListener('click', function(e){
      const form = e.target.closest('.js-delete-assessment');
      if(form){
        if(!confirm('Are you sure you want to delete this assessment? This action cannot be undone.')){
          e.preventDefault();
        }
      }
    });
  </script>

  <style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
    *{font-family:'Inter',-apple-system,BlinkMacSystemFont,sans-serif;}
    :root{
      --purple-50:#faf5ff;--purple-100:#f3e8ff;--purple-200:#e9d5ff;--purple-300:#d8b4fe;--purple-400:#c084fc;
      --purple-500:#a855f7;--purple-600:#9333ea;--purple-700:#7c3aed;--purple-800:#6b21a8;--purple-900:#581c87;
      --gradient-primary:linear-gradient(135deg,var(--purple-600) 0%,var(--purple-800) 100%);
      --gradient-button:linear-gradient(135deg,var(--purple-700) 0%,var(--purple-900) 100%);
      --red-grad:linear-gradient(135deg,#ef4444 0%,#b91c1c 100%);
      --shadow-sm:0 1px 2px rgb(0 0 0 / .05);
      --shadow-lg:0 10px 15px -3px rgb(0 0 0 / .1),0 4px 6px -4px rgb(0 0 0 / .1);
      --shadow-xl:0 20px 25px -5px rgb(0 0 0 / .1),0 8px 10px -6px rgb(0 0 0 / .1);
    }
    /* Header */
    .form-header-container{background:var(--gradient-primary);padding:1.25rem 1.5rem;border-radius:0 0 1.25rem 1.25rem;position:relative;overflow:hidden;}
    .form-header-container::before{content:'';position:absolute;inset:0;background:url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E") repeat;opacity:.1;pointer-events:none;}
    .header-flex{display:flex;align-items:center;justify-content:space-between;gap:1rem}
    .header-left{display:flex;align-items:center;gap:1rem}
    .form-icon-wrapper{width:3.25rem;height:3.25rem;border-radius:1rem;background:rgba(255,255,255,.2);display:flex;align-items:center;justify-content:center;backdrop-filter:blur(8px)}
    .form-icon-wrapper i{font-size:1.35rem;color:#fbbf24}
    .form-title{font-size:1.6rem;font-weight:800;color:#fff;margin:0;letter-spacing:-.02em}
    .form-subtitle{margin:.15rem 0 0;color:rgba(255,255,255,.85)}

    /* Body */
    body{background:linear-gradient(135deg,rgba(124,58,237,.03),rgba(248,250,252,1))}
    .form-container{width:100%;margin:0;padding:1.25rem 1.5rem;min-height:calc(100vh - 120px);background:linear-gradient(135deg,#f8fafc 0%,#f1f5f9 100%)}

    /* Flash */
    .flash{display:flex;align-items:center;gap:.6rem;margin:0 0 12px;padding:.8rem 1rem;border-radius:.65rem;border:1px solid}
    .flash.success{background:#ecfdf5;border-color:#a7f3d0;color:#065f46}
    .flash.error{background:#fef2f2;border-color:#fecaca;color:#7f1d1d}

    /* Assessments card */
    .assessments-card{width:100%;background:#fff;border:1px solid rgba(139,92,246,.15);border-radius:.75rem;box-shadow:var(--shadow-lg);overflow:hidden;margin:0 0 1rem}
    .assessments-header{background:var(--purple-50);padding:1.25rem 1.5rem;border-bottom:1px solid var(--purple-200)}
    .assessments-grid{display:grid;grid-template-columns:1fr;gap:.75rem;padding:1rem 1.25rem}
    @media (min-width: 900px){.assessments-grid{grid-template-columns:1fr 1fr}}
    .assess-tile{display:flex;align-items:center;justify-content:space-between;gap:1rem;background:#fff;border:1px solid #e5e7eb;border-radius:.75rem;padding:1rem 1.25rem}
    .tile-left{display:flex;align-items:center;gap:.9rem}
    .tile-icon{width:44px;height:44px;border-radius:.65rem;display:flex;align-items:center;justify-content:center;color:#fff;box-shadow:var(--shadow-sm)}
    .tile-icon.pre{background:linear-gradient(135deg,#22c55e,#16a34a)}
    .tile-icon.post{background:linear-gradient(135deg,#ef4444,#b91c1c)}
    .tile-title{font-weight:800;font-size:1.05rem}
    .tile-sub{font-size:.95rem;color:#334155}
    .tile-sub.muted{color:#64748b}
    .tile-actions{display:flex;gap:.5rem;align-items:center}
    .inline{display:inline}
    .btn-primary,.btn-secondary,.btn-danger{display:inline-flex;align-items:center;gap:.5rem;border:none;cursor:pointer;padding:.7rem 1rem;border-radius:.65rem;font-weight:800;transition:transform .15s,box-shadow .2s;text-decoration:none;font-size:.95rem}
    .btn-primary{background:var(--gradient-button);color:#fff;box-shadow:var(--shadow-lg)}
    .btn-primary:hover{transform:translateY(-1px);box-shadow:var(--shadow-xl);color:#fff}
    .btn-secondary{background:#f8fafc;color:#64748b;border:2px solid #e5e7eb}
    .btn-secondary:hover{background:#f1f5f9;color:#475569;border-color:#cbd5e1;transform:translateY(-1px)}
    .btn-danger{background:var(--red-grad);color:#fff;box-shadow:var(--shadow-lg)}
    .btn-danger:hover{transform:translateY(-1px);box-shadow:var(--shadow-xl);color:#fff}

    /* Form card */
    .form-card{width:100%;background:#fff;border:1px solid rgba(139,92,246,.15);border-radius:.75rem;box-shadow:var(--shadow-lg);overflow:hidden}
    .form-card-header{background:var(--purple-50);padding:1.25rem 1.5rem;border-bottom:1px solid var(--purple-200)}
    .card-title{margin:0 0 .35rem;font-size:1.25rem;font-weight:800;color:var(--purple-900)}
    .card-description{margin:0;color:#64748b}
    .stage-form{padding:1.25rem 1.5rem}
    .form-group{margin-bottom:1.25rem}
    .form-label{display:block;font-size:.8rem;font-weight:700;color:var(--purple-900);margin-bottom:.35rem;text-transform:uppercase;letter-spacing:.05em}
    .form-input,.form-textarea{width:100%;padding:.85rem 1rem;background:#fff;color:#111827;border:2px solid #e5e7eb;border-radius:.75rem;font-size:1rem;transition:box-shadow .2s,border-color .2s}
    .form-input:hover,.form-textarea:hover{border-color:var(--purple-200)}
    .form-input:focus,.form-textarea:focus{outline:none;border-color:var(--purple-400);box-shadow:0 0 0 4px rgba(168,85,247,.15)}
    .form-textarea{resize:vertical;min-height:120px;font-family:inherit}
    .form-help{font-size:.9rem;color:#6b7280;margin-top:.35rem}
    .form-actions{display:flex;gap:.75rem;padding-top:1rem;border-top:1px solid #e5e7eb;margin-top:1.25rem;flex-wrap:wrap}
    .ml-4{margin-left:1rem}
    @media (max-width:900px){.header-flex{flex-direction:column;align-items:stretch;gap:1rem}}
  </style>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</x-app-layout>
