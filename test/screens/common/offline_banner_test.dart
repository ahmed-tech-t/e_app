import 'dart:async';

import 'package:flutter/material.dart';
import 'package:flutter_test/flutter_test.dart';
import 'package:pos_mobile/core/services/network_connectivity_service.dart';
import 'package:pos_mobile/screens/common/offline_banner.dart';

class MockNetworkConnectivityService implements NetworkConnectivityService {
  final StreamController<bool> _controller = StreamController<bool>.broadcast();
  bool mockIsOffline = false;

  void emitOffline(bool isOffline) {
    mockIsOffline = isOffline;
    _controller.add(isOffline);
  }

  @override
  Stream<bool> get isOfflineStream => _controller.stream;

  @override
  Future<bool> checkIsOffline() async => mockIsOffline;

  @override
  dynamic noSuchMethod(Invocation invocation) => super.noSuchMethod(invocation);
}

void main() {
  testWidgets(
    'OfflineBanner displays warning message when stream emits offline true',
    (tester) async {
      final mockService = MockNetworkConnectivityService();

      await tester.pumpWidget(
        MaterialApp(
          home: Scaffold(
            body: Column(
              children: [
                OfflineBanner(service: mockService),
                const Expanded(child: Center(child: Text('App Content'))),
              ],
            ),
          ),
        ),
      );

      // Initial online state: AnimatedCrossFade shows firstChild (SizedBox(height: 0))
      expect(find.text('No Internet Connection — Offline Mode'), findsNothing);

      // Emit offline true through the stream
      mockService.emitOffline(true);
      await tester.pump();
      await tester.pump(const Duration(milliseconds: 350));

      // Warning is now visible
      expect(
        find.text('No Internet Connection — Offline Mode'),
        findsOneWidget,
      );
      expect(find.byIcon(Icons.wifi_off_rounded), findsOneWidget);

      // Emit online false
      mockService.emitOffline(false);
      await tester.pump();
      await tester.pump(const Duration(milliseconds: 350));

      // Warning is hidden again
      expect(find.text('No Internet Connection — Offline Mode'), findsNothing);
    },
  );
}
