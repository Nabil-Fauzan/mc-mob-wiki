<x-app-layout>
    <div class="py-12 px-4 sm:px-6 lg:px-8 min-h-screen">
        <div class="max-w-7xl mx-auto">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-3xl font-black text-white tracking-tight uppercase">Contribution Queue</h1>
                    <p class="text-gray-400 mt-2">Review suggested edits submitted by community researchers.</p>
                </div>
                <a href="{{ route('admin.dashboard') }}" class="text-brand-500 hover:text-brand-400 font-bold text-sm transition-colors">
                    &larr; Back to Master Control
                </a>
            </div>

            @if(session('success'))
                <div class="mb-8 p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded-2xl font-bold">
                    {{ session('success') }}
                </div>
            @endif

            @if($contributions->isNotEmpty())
            <!-- Bulk Actions Bar -->
            <div class="mb-6 bg-gray-900/50 border border-white/5 rounded-2xl p-4 flex items-center justify-between">
                <label class="flex items-center space-x-3 cursor-pointer group">
                    <input type="checkbox" id="selectAll" class="w-5 h-5 rounded border-white/10 bg-black/40 text-brand-500 focus:ring-brand-500 focus:ring-offset-gray-900 transition-all cursor-pointer">
                    <span class="text-sm font-bold text-gray-300 group-hover:text-white transition-colors">Select All</span>
                </label>
                <div class="flex items-center gap-3">
                    <button type="button" onclick="submitBulkAction('reject')" class="px-4 py-2 bg-red-500/10 hover:bg-red-500/20 text-red-500 font-bold rounded-xl border border-red-500/20 transition-all text-xs uppercase tracking-widest hidden" id="bulkRejectBtn">
                        Bulk Reject
                    </button>
                    <button type="button" onclick="submitBulkAction('approve')" class="px-4 py-2 bg-emerald-500 hover:bg-emerald-400 text-white font-bold rounded-xl shadow-[0_0_15px_rgba(16,185,129,0.4)] transition-all text-xs uppercase tracking-widest hidden" id="bulkApproveBtn">
                        Bulk Approve
                    </button>
                </div>
            </div>

            <!-- Hidden form for bulk actions -->
            <form id="bulkActionForm" method="POST" class="hidden">
                @csrf
                <div id="bulkInputs"></div>
            </form>
            @endif

            <div class="grid grid-cols-1 gap-6">
                @forelse($contributions as $contribution)
                    <div class="glass-card p-6 sm:p-8 rounded-[2rem] border-white/5 relative overflow-hidden transition-all duration-200" id="card-{{ $contribution->id }}">
                        <!-- Selection Checkbox -->
                        <div class="absolute top-6 right-6">
                            <input type="checkbox" value="{{ $contribution->id }}" data-score="{{ $contribution->ai_trust_score ?? 100 }}" class="contribution-checkbox w-6 h-6 rounded-lg border-white/10 bg-black/40 text-brand-500 focus:ring-brand-500 focus:ring-offset-gray-900 transition-all cursor-pointer shadow-lg z-10" onchange="toggleCardStyle({{ $contribution->id }}, this.checked); updateBulkButtons()">
                        </div>

                        <div class="flex flex-col lg:flex-row gap-8">
                            <!-- Info Column -->
                            <div class="lg:w-1/3 pt-4 lg:pt-0">
                                <div class="flex items-center space-x-3 mb-4">
                                    <img src="{{ $contribution->user->avatar_url }}" alt="{{ $contribution->user->name }}" class="w-10 h-10 rounded-full border border-white/10">
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <p class="text-sm font-bold text-white">{{ $contribution->user->name }}</p>
                                            @if($contribution->user->active_title)
                                                <span class="px-2 py-0.5 rounded bg-amber-500/10 border border-amber-500/20 text-[9px] font-black uppercase tracking-widest text-amber-400">
                                                    {{ $contribution->user->active_title }}
                                                </span>
                                            @endif
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1">Lv. {{ $contribution->user->level }} Researcher</p>
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <p class="text-xs text-gray-400 uppercase tracking-widest font-bold">Target Entity</p>
                                    <div class="flex items-center space-x-3 bg-gray-900/50 p-3 rounded-xl border border-white/5">
                                        <img src="{{ asset('storage/' . $contribution->mob->image) }}" class="w-12 h-12 object-contain" alt="">
                                        <div>
                                            <p class="text-white font-bold">{{ $contribution->mob->name }}</p>
                                            <a href="{{ route('mobs.show', $contribution->mob) }}" target="_blank" class="text-[10px] text-brand-500 hover:text-brand-400 uppercase tracking-widest">View Live &rarr;</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <p class="text-xs text-gray-400 uppercase tracking-widest font-bold mb-1">Target Field</p>
                                    <span class="px-3 py-1 bg-white/5 text-white rounded-lg text-xs font-mono border border-white/10">{{ ucfirst($contribution->field) }}</span>
                                </div>
                                <p class="text-xs text-gray-500 mt-4">{{ $contribution->created_at->diffForHumans() }}</p>
                            </div>

                            <!-- Diff Column -->
                            <div class="lg:w-2/3 space-y-4">
                                @if($contribution->ai_assessment)
                                    @php
                                        $scoreColor = 'text-gray-400';
                                        $scoreBg = 'bg-gray-500/10 border-gray-500/20';
                                        if ($contribution->ai_trust_score >= 80) {
                                            $scoreColor = 'text-emerald-400';
                                            $scoreBg = 'bg-emerald-500/10 border-emerald-500/20';
                                        } elseif ($contribution->ai_trust_score <= 40) {
                                            $scoreColor = 'text-rose-400';
                                            $scoreBg = 'bg-rose-500/10 border-rose-500/20';
                                        } else {
                                            $scoreColor = 'text-amber-400';
                                            $scoreBg = 'bg-amber-500/10 border-amber-500/20';
                                        }
                                    @endphp
                                    <div class="mb-4 {{ $scoreBg }} border rounded-xl p-4">
                                        <div class="flex items-center justify-between mb-2">
                                            <div class="flex items-center gap-2">
                                                <svg class="w-5 h-5 {{ $scoreColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                                <span class="text-xs font-black {{ $scoreColor }} uppercase tracking-widest">AI Moderation</span>
                                            </div>
                                            <span class="text-sm font-bold {{ $scoreColor }}">Trust Score: {{ $contribution->ai_trust_score ?? '?' }}%</span>
                                        </div>
                                        <p class="text-sm text-gray-300">{{ $contribution->ai_assessment }}</p>
                                    </div>
                                @endif

                                <div>
                                    <p class="text-[10px] text-red-400 uppercase tracking-widest font-bold mb-2">Current Value</p>
                                    <div class="bg-red-500/5 border border-red-500/20 rounded-xl p-4 text-sm text-red-200/80 whitespace-pre-wrap">
                                        {{ $contribution->mob->{$contribution->field} ?? 'N/A' }}
                                    </div>
                                </div>
                                <div class="flex justify-center">
                                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                                </div>
                                <div>
                                    <p class="text-[10px] text-green-400 uppercase tracking-widest font-bold mb-2">Proposed Value</p>
                                    <div class="bg-green-500/5 border border-green-500/20 rounded-xl p-4 text-sm text-green-200/80 whitespace-pre-wrap">
                                        {{ $contribution->proposed_value }}
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="flex justify-end gap-4 mt-6 pt-6 border-t border-white/5">
                                    <form method="POST" action="{{ route('admin.contributions.reject', $contribution) }}">
                                        @csrf
                                        <button type="submit" class="px-6 py-2.5 bg-red-500/10 hover:bg-red-500/20 text-red-500 font-bold rounded-xl border border-red-500/20 transition-all text-sm">
                                            Reject
                                        </button>
                                    </form>
                                    @if($contribution->ai_trust_score === 0)
                                        <button type="button" onclick="alert('Mohon cek kembali sugest yang diterima, karena sistem mendeteksi kemungkinan benar sangat kecil')" class="px-6 py-2.5 bg-gray-600 hover:bg-gray-500 text-white font-bold rounded-xl transition-all text-sm cursor-not-allowed" title="System has blocked approval due to 0% trust score.">
                                            Approve & Merge
                                        </button>
                                    @else
                                        <form method="POST" action="{{ route('admin.contributions.approve', $contribution) }}" onsubmit="return confirmApproval({{ $contribution->ai_trust_score ?? 100 }})">
                                            @csrf
                                            <button type="submit" class="px-6 py-2.5 bg-emerald-500 hover:bg-emerald-400 text-white font-bold rounded-xl shadow-[0_0_15px_rgba(16,185,129,0.4)] transition-all text-sm">
                                                Approve & Merge
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="glass-card p-12 rounded-[2rem] text-center border-white/5">
                        <div class="w-20 h-20 bg-white/5 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-10 h-10 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-2">Queue is Empty</h3>
                        <p class="text-gray-400">There are no pending contributions to review at this time.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectAll = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.contribution-checkbox');
            
            if(selectAll) {
                selectAll.addEventListener('change', function() {
                    checkboxes.forEach(cb => {
                        cb.checked = this.checked;
                        toggleCardStyle(cb.value, cb.checked);
                    });
                    updateBulkButtons();
                });
            }
        });

        function toggleCardStyle(id, isChecked) {
            const card = document.getElementById('card-' + id);
            if (isChecked) {
                card.classList.add('ring-2', 'ring-brand-500', 'bg-brand-500/5');
            } else {
                card.classList.remove('ring-2', 'ring-brand-500', 'bg-brand-500/5');
            }
        }

        function updateBulkButtons() {
            const anyChecked = document.querySelectorAll('.contribution-checkbox:checked').length > 0;
            const approveBtn = document.getElementById('bulkApproveBtn');
            const rejectBtn = document.getElementById('bulkRejectBtn');
            
            if (approveBtn && rejectBtn) {
                if (anyChecked) {
                    approveBtn.classList.remove('hidden');
                    rejectBtn.classList.remove('hidden');
                } else {
                    approveBtn.classList.add('hidden');
                    rejectBtn.classList.add('hidden');
                }
            }
        }

        function confirmApproval(score) {
            if (score === 0) {
                alert("Mohon cek kembali sugest yang diterima, karena sistem mendeteksi kemungkinan benar sangat kecil");
                return false;
            } else if (score < 25) {
                return confirm("Sugest kemungkinan besar salah, yakin tetap lanjutkan?");
            } else if (score <= 50) {
                return confirm("Sugest kemungkinan salah, mohon cek kembali sebelum melanjutkan");
            }
            return true;
        }

        function submitBulkAction(action) {
            const checked = document.querySelectorAll('.contribution-checkbox:checked');
            if (checked.length === 0) return;

            const form = document.getElementById('bulkActionForm');
            const inputsDiv = document.getElementById('bulkInputs');
            
            // Set form action
            if (action === 'approve') {
                let lowestScore = 100;
                checked.forEach(cb => {
                    let score = parseInt(cb.dataset.score);
                    if (!isNaN(score) && score < lowestScore) {
                        lowestScore = score;
                    }
                });

                if (lowestScore === 0) {
                    alert("Mohon cek kembali sugest yang diterima, karena sistem mendeteksi kemungkinan benar sangat kecil pada salah satu usulan yang dipilih");
                    return;
                } else if (lowestScore < 25) {
                    if(!confirm(`Terdapat usulan dengan kemungkinan besar salah, yakin tetap menyetujui ${checked.length} usulan ini?`)) return;
                } else if (lowestScore <= 50) {
                    if(!confirm(`Terdapat usulan yang kemungkinan salah, mohon cek kembali sebelum menyetujui ${checked.length} usulan ini. Lanjutkan?`)) return;
                } else {
                    if(!confirm(`Are you sure you want to approve ${checked.length} contributions?`)) return;
                }
                
                form.action = "{{ route('admin.contributions.bulk_approve') }}";
            } else {
                if(!confirm(`Are you sure you want to reject ${checked.length} contributions?`)) return;
                form.action = "{{ route('admin.contributions.bulk_reject') }}";
            }

            // Populate hidden inputs
            inputsDiv.innerHTML = '';
            checked.forEach(cb => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'contribution_ids[]';
                input.value = cb.value;
                inputsDiv.appendChild(input);
            });

            form.submit();
        }
    </script>
    @endpush
</x-app-layout>
