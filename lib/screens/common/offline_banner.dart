import 'package:flutter/material.dart';
import 'package:pos_mobile/core/services/network_connectivity_service.dart';
import 'package:pos_mobile/core/theme/app_colors.dart';

class OfflineBanner extends StatefulWidget {
  final NetworkConnectivityService? service;

  const OfflineBanner({super.key, this.service});

  @override
  State<OfflineBanner> createState() => _OfflineBannerState();
}

class _OfflineBannerState extends State<OfflineBanner> {
  late final NetworkConnectivityService _service;
  bool _isOffline = false;

  @override
  void initState() {
    super.initState();
    _service = widget.service ?? NetworkConnectivityService.instance;
    _checkInitialStatus();
  }

  Future<void> _checkInitialStatus() async {
    final offline = await _service.checkIsOffline();
    if (mounted && offline != _isOffline) {
      setState(() {
        _isOffline = offline;
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    return StreamBuilder<bool>(
      stream: _service.isOfflineStream,
      initialData: _isOffline,
      builder: (context, snapshot) {
        final isOffline = snapshot.data ?? _isOffline;

        return AnimatedCrossFade(
          firstChild: const SizedBox(width: double.infinity, height: 0),
          secondChild: isOffline
              ? Material(
                  color: AppColors.error,
                  elevation: 2,
                  child: SafeArea(
                    bottom: false,
                    minimum: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
                    child: Row(
                      mainAxisAlignment: MainAxisAlignment.center,
                      children: const [
                        Icon(
                          Icons.wifi_off_rounded,
                          color: Colors.white,
                          size: 18,
                        ),
                        SizedBox(width: 8),
                        Flexible(
                          child: Text(
                            'No Internet Connection — Offline Mode',
                            style: TextStyle(
                              color: Colors.white,
                              fontSize: 13,
                              fontWeight: FontWeight.w600,
                              letterSpacing: 0.2,
                            ),
                            textAlign: TextAlign.center,
                            overflow: TextOverflow.ellipsis,
                          ),
                        ),
                      ],
                    ),
                  ),
                )
              : const SizedBox(width: double.infinity, height: 0),
          crossFadeState: isOffline ? CrossFadeState.showSecond : CrossFadeState.showFirst,
          duration: const Duration(milliseconds: 300),
        );
      },
    );
  }
}

/// Widget Preview
Widget previewOfflineBanner() {
  return const MaterialApp(
    home: Scaffold(
      body: Column(
        children: [
          Material(
            color: AppColors.error,
            child: Padding(
              padding: EdgeInsets.symmetric(horizontal: 16, vertical: 8),
              child: Row(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  Icon(Icons.wifi_off_rounded, color: Colors.white, size: 18),
                  SizedBox(width: 8),
                  Text(
                    'No Internet Connection — Offline Mode',
                    style: TextStyle(
                      color: Colors.white,
                      fontSize: 13,
                      fontWeight: FontWeight.w600,
                    ),
                  ),
                ],
              ),
            ),
          ),
        ],
      ),
    ),
  );
}
